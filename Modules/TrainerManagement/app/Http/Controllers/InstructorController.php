<?php

namespace Modules\TrainerManagement\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Lms\Models\Classe;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LmsSyncService;
use App\Events\UserAssignedToClass;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Models\ExternalClasse;
use Spatie\QueryBuilder\AllowedFilter;
use Modules\TrainerManagement\Models\Instructor;
use App\Mail\{ApologyInstructorMail ,WelcomeInstructorMail};
use Illuminate\Support\Facades\{Auth ,Hash ,Mail ,Validator};
use Modules\TrainerManagement\Http\Resources\InstructorResource;
use Modules\TrainerManagement\Http\Requests\{SetInstructorRequest ,CreateInstructorRequest ,UpdateInstructorRequest};

class InstructorController extends Controller
{

    public function getInstructorProfilePdf($slug)
    {
        // Retrieve instructor data
        $instructor = Instructor::with('user')
            ->where('slug', $slug)
            ->firstOrFail();
            // return $instructor;
        $url = env('WEBSITE_URL');
        // Generate PDF using spatie/laravel-pdf
        $pdf = Pdf::loadView('new.instructor-pdf', [
            'instructor' => $instructor,
            'user' => $instructor->user,
            'website_url' => $url,

        ])->setPaper('A4', 'portrait')

        ;

        $fileName = 'cv_' . $instructor->slug . '.pdf';
        $filePath = public_path('cv/' . $fileName);

        // Ensure the directory exists
        if (!file_exists(public_path('cv'))) {
            mkdir(public_path('cv'), 0777, true);
        }

        // Save the PDF file to the public directory
        file_put_contents($filePath, $pdf->output());

        $shareableLink = url('cv/' . $fileName);

        return response()->json([
            'link' => $shareableLink,
        ]);
    }

    public function index(Request $request)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))) {
        //     return ResponseHelper::authorizationFail(__('message.unauthorized'));
        // }

        // $query = QueryBuilder::for(Instructor::class)
        //     ->allowedFilters(['name'])
        //     ->allowedSorts(['name', 'created_at']);
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if ($role->name === 'admin' || $role->name === 'supervisor') {
            $query = QueryBuilder::for(Instructor::class)
                ->with(['user','city','topics'])
                ->allowedFilters([
                    'location',
                    // AllowedFilter::custom('location', new \App\Http\Filters\LocationFilter()),
                    'availability',
                    'category_id',
                    'rating'
                ])
                ->allowedSorts(['created_at']);
            // ->when(request('location'), function ($query, $location) {
            //     $location = request('location');
            //     return $query->where('location', 'like', "%{$location}%");
            // });


        } elseif ($role->name === 'trainer') {
            $query = QueryBuilder::for(Instructor::class)
                ->where('user_id', $user->id)
                ->with('user')
                ->allowedFilters([
                    'location',
                    // AllowedFilter::custom('location', new \App\Http\Filters\LocationFilter()),
                    'availability',
                    'category_id',
                    'rating'
                ])
                ->allowedSorts(['created_at']);
            // ->when(request('location'), function ($query, $location) {
            //     $location = request('location');
            //     return $query->where('location', 'like', "%{$location}%");
            // });
        } else {
            return ResponseHelper::authorizationFail(__('message.unauthorized'));
        }
        $instructors = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'instructors' => InstructorResource::collection($instructors),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $instructors->total(),
                'per_page' => $instructors->perPage(),
                'current_page' => $instructors->currentPage(),
                'last_page' => $instructors->lastPage(),
                'from' => $instructors->firstItem(),
                'to' => $instructors->lastItem(),
                'links' => [
                    'first' => $instructors->url(1),
                    'last' => $instructors->url($instructors->lastPage()),
                    'prev' => $instructors->previousPageUrl(),
                    'next' => $instructors->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    public function findByLocation(Request $request)
    {
        $location = $request->input('location');
        $topics =$request->input('topics');
        $instructors = Instructor::with('user')
        ->when(!empty($location), function ($query) use ($location) {
            return $query->whereHas('city', function ($subQuery) use ($location) {
                $subQuery->where('id', $location);
            });
        })
        ->when(!empty($topics), function ($query) use ($topics) {
            return $query->whereHas('topics', function ($subQuery) use ($topics) {
                $subQuery->whereIn('topics.id', $topics);
            });
        })
        ->get();
        return ResponseHelper::success(InstructorResource::collection($instructors));
    }

    public function find(Request $request)
    {
        $instructor = Instructor::with('user')
            ->where('slug', $request->input('slug'))
            ->firstOrFail();
        return ResponseHelper::success(new InstructorResource($instructor));
    }

    public function findInstructorById(Request $request)
    {
        $instructor = Instructor::with('user')
            ->findOrFail($request->input('user_id'));

        return ResponseHelper::success(new InstructorResource($instructor));
    }

    public function create(CreateInstructorRequest $request)
    {
        $currentUser = Auth::user();
        $currentRole = Role::find($currentUser->current_role_id);

     // Create the user
        $user = User::create([
            'name' => $request->name,
            'middel_name' => $request->middel_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'image' => $request->image,
            'first_login' => true,
        ]);

        LmsSyncService::sync($user,'create');
        //restrict permission by pre-trainer role
        $role = Role::where('name', 'pre_trainer')->first();
        $user->assignRole($role);
        $user->update(['role' => $role->id, 'current_role_id' => $role->id]);
        LmsSyncService::syncUserRoles($user);
         // Generate a unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        \Log::info('slug content :', ['name' => $request->name,'baseSlug' => $baseSlug, 'slug' => $slug]);
        
        
        while (Instructor::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        \Log::info('slug :', ['slug' => $slug]);

        // Create the instructor data
        $instructor = Instructor::create([
            'user_id' => $user->id,
            'location' => $request->location,
            'slug' => $slug,
            'rating' => $request->rate,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'whatsapp' => $request->whatsapp,
            'work_history' => $request->work_history,
            'category_id' => $request->category_id,
            'professional_summary' => $request->professional_summary,
            'experience' => json_encode($request->experience),
            'qualification' => json_encode($request->qualification),
            'certification' => json_encode($request->certification),
            'course_experience_lpc' => json_encode($request->course_experience_lpc),
            // 'specilized_topics' => json_encode($request->specilized_topics),
            'languages' => json_encode($request->input('languages')),
            'awards' => json_encode($request->awards),
            'testimonials' => json_encode($request->testimonials),
            'social_media_links' => json_encode($request->social_media_links),
            'portofolio_url' => $request->portofolio_url,
            'linkedln_url' => $request->linkedln_url,
            'training_modes' => json_encode($request->training_modes),
            'availability' => json_encode($request->availability),
            'country_availability' => json_encode($request->country_availability),
            'speaking_engagements' => json_encode($request->engagement),
            'publications' => json_encode($request->publication),
            'date_of_submission' => now(),
        ]);
        
        if ($request->has('specilized_topics') && is_array($request->specilized_topics)) {
            $instructor->topics()->sync($request->specilized_topics);
            $instructor->load('topics');
        }
        LmsSyncService::sync($instructor, 'create');
        event(new UserAssignedToClass($user));

        // Return response with instructor and user data
        return ResponseHelper::create(new InstructorResource($instructor->load('user')));
    }

    public function update(Instructor $instructor, UpdateInstructorRequest $request)
    {
        $user = User::findOrFail($instructor->user_id);

        // Update the user's details
        $user->update([
            'name' => $request->name,
            'middel_name' => $request->middel_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $request->image
        ]);

         $slug = $instructor->slug;
        if ($request->name !== $user->name || empty($instructor->slug)) {
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;
            $counter = 1;

            while (
                Instructor::where('slug', $slug)
                    ->where('id', '!=', $instructor->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter++;
            }
        }

        // Update the instructor's details
        $instructor->update([
            'location' => $request->input('location'),
            'slug' => $slug,
            'rating' => $request->rate,
            'facebook' => $request->input('facebook'),
            'instagram' => $request->input('instagram'),
            'twitter' => $request->input('twitter'),
            'whatsapp' => $request->input('whatsapp'),
            'work_history' => $request->input('work_history'),
            'category_id' => $request->input('category_id'),
            'professional_summary' => $request->input('professional_summary'),

            'experience' => json_encode($request->input('experience')),
            'qualification' => json_encode($request->input('qualification')),
            'certification' => json_encode($request->input('certification')),
            'course_experience_lpc' => json_encode($request->input('course_experience_lpc')),
            // 'specilized_topics' => json_encode($request->input('specilized_topics')),
            'languages' => json_encode($request->input('languages')),
            'awards' => json_encode($request->input('awards')),
            'testimonials' => json_encode($request->input('testimonials')),
            'social_media_links' => json_encode($request->input('social_media_links')),
            'portofolio_url' => $request->input('portofolio_url'),
            'linkedln_url' => $request->input('linkedln_url'),
            'training_modes' => json_encode($request->input('training_modes')),
            'availability' => json_encode($request->input('availability')),
            'country_availability' => json_encode($request->input('country_availability')),
            'speaking_engagements' => json_encode($request->input('engagement')),
            'publications' => json_encode($request->input('publication')),

            'date_of_submission' => now(),
        ]);
        if ($request->has('specilized_topics') && is_array($request->specilized_topics)) {
            $instructor->topics()->sync($request->specilized_topics);
        }
        $instructor->load('topics');
        LmsSyncService::sync($instructor, 'update');
        $current_user=Auth::user();
        $current_role=Role::find($current_user->role);
        // remove restricted on permission after update profile
        if ($current_role->name=='pre_trainer'&& $current_user->id == $user->id && $current_user->first_login == true)
        {

            $trainerRole = Role::where('name','trainer')->first();
            $user->removeRole($current_role);
            $user->assignRole($trainerRole);
            $current_user->update([
                'first_login' => false,
                'current_role_id' => $trainerRole->id,
                'role'=>$trainerRole->id
            ]);
            LmsSyncService::syncUserRoles($user);

            // return 'ok';
        }
        // Return the updated instructor resource
        return ResponseHelper::success(new InstructorResource($instructor->load('user')));
    }

    public function delete(Instructor $instructor)
    {
        $user = User::find($instructor->user_id);

        $instructor->delete();
        $user->delete();

        return ResponseHelper::success();
    }
    public function generateCV($instructorId, Request $request)
    {
        $instructor = Instructor::with('user')->where('user_id', $instructorId)->first();

        return ResponseHelper::success([
            // 'link' => $shareableLink,
            'link_cv' => "apps/instructors/profile/{$instructor->slug}",
        ]);
    }

    public function addRating(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        $instructor = Instructor::where('user_id', $request->instructor_id)->first();

        if ($instructor) {
            $instructor->rating = $request->rating;
            $instructor->save();
        }
        return ResponseHelper::success();
    }

    public function setInstructor(SetInstructorRequest $request)
    {

        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        $typeCourse = $request->input('type', 'official');

        if (!$role || !in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $instructor = User::find($request->instructor_id);
        

        $class =  Classe::where('id', $request->input('class_id'))
                  ->where('course_type', $typeCourse)
                  ->first();

        \Log::info('Instructor check',[
        'instructor_id_from_uuid' => $request->instructor_id,
            'instructor' => $instructor,
            'class' => $class,
            'typeCourse' => $typeCourse
        ]);
        if (!$instructor || !$class) {
            return ResponseHelper::DataNotFound();
        }

        if (!$class) {
            return ResponseHelper::DataNotFound('Class not found.');
        }
        $class->trainer_id = $instructor->id;
        $class->save();

        Mail::to($instructor->email)->queue(new WelcomeInstructorMail($class, $instructor));

        $availableInstructors = User::whereHas('announcements', function ($query) use ($class) {
            $query->where('classe_id', $class->id)
                ->where('type', 'typeClass')
                ->where('available', '1');
        })->where('id', '!=', $instructor->id)->get();

        foreach ($availableInstructors as $availableInstructor) {
            Mail::to($availableInstructor->email)->queue(new ApologyInstructorMail($class, $availableInstructor));
        }
        return ResponseHelper::success();
    }

    public function getInstructorProfile($slug)
    {
        // Validate the slug parameter
        $validatedSlug = Validator::make(['slug' => $slug], [
            'slug' => 'required|string|exists:instructors,slug',
        ])->validated()['slug'];

        $instructor = Instructor::with('user')
            ->where('slug', $validatedSlug)
            ->firstOrFail();
        $url = env('WEBSITE_URL');

        return view('new.instructor', [
            'instructor' => $instructor,
            'website_url' => $url,
            'user' => $instructor->user,
        ]);
    }

    public function getInstructorProfileUrl($slug)
    {
    }
}
