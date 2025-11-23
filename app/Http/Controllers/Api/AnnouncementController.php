<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\MailLog;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Mail\CourseAnnouncements;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\AvailabilityRequest;
use App\Http\Requests\CourseAnnouncementRequest;
use App\Http\Resources\CourseAnnouncementResource;
use App\Notifications\CourseAnnouncementsNotification;
use Modules\Lms\Models\{Course,Classe,Company, ExternalClasse, ExternalCourse, Session,Schedule};

class AnnouncementController extends Controller
{


    /**
     * send to instructor
     * @param \App\Http\Requests\CourseAnnouncementRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendCourseAnnouncements(CourseAnnouncementRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $typeCourse = $request->input('course_type', 'official');
        $courseModel = $typeCourse === 'custom' ? ExternalCourse::class : Course::class;

        $course = $courseModel::find($request->input('course_id'));
        $class = Classe::where('id', $request->input('class_id'))
              ->where('course_type', $typeCourse)
              ->first();


        if (!$course || !$class) {
            return ResponseHelper::operationFail('Course or Class not found.');
        }

        $instructors = User::whereIn('id', $request->input('instructor_ids'))->get();
        $image=$request->input('image')??null;
        $attachments=$request->input('attachments');
        // return $attachments;
        foreach ($instructors as $instructor) {
            $announcement=Announcement::where('user_id',$instructor->id)
                ->where('classe_id',$class->id)
                ->where('course_id',$course->id)
                ->where('type',$typeCourse)->first();
            if(!$announcement){
                Announcement::create([
                    'user_id'=>$instructor->id,
                    'classe_id' => $class->id,
                    'course_id' => $course->id,
                    'type' => $typeCourse,
                    'attachments' => $attachments,
                    'image'=>$image
                ]);
            }
            $sent_by= Auth::id();
            $template =  $validated['template'];
            $subject = $template['subject'];

            // Step 1: Decode JSON string
            $rawBody = $template['html_body'] ?? '';

            if (is_null($rawBody)) {
                return ResponseHelper::operationFail('Template body is not valid JSON');
            }

            // If it's wrapped in double quotes, it's JSON-encoded
            if (str_starts_with($rawBody, '"')) {
                $rawBody = json_decode($rawBody);
            }

            // Step 2: Remove leftover slashes (if still present)
            $rawBody = stripslashes($rawBody);

            // Step 3: Replace placeholders
            $replacedBody = str_replace(
                ['{{course_name}}', '{{trainer_name}}', '{{class_title}}', '{{url}}', '{{city}}'],
                [$course->name ,  $instructor->name ,$class->title ,env('PROJECT_FRONTEND_LMS')
                ,$class->schedule?->city?->name ?? ''],
                $rawBody
            );

            // Step 4: Decode any HTML entities
            $replacedBody = html_entity_decode($replacedBody);
            Log::info('before notification section' . $course->id);

                if ($template['status'] == 0) {
                    // Save as draft
                    MailLog::create([
                        'recipient' => $instructor->email,
                        'name' => $instructor->name,
                        'subject' => $template['subject'] ?? 'No Subject',
                        'body' => $replacedBody,
                        'status' => 0,
                        'user_id' => $sent_by,
                        'attachments' => $attachments,
                    ]);
                } else {
                    Log::info('reach to notification section ' . $instructor->id);
                    $instructor->notify(new CourseAnnouncementsNotification($course->id, $class->id, $instructor->id));
                    Mail::to($instructor->email)->queue(
                        new CourseAnnouncements($course, $class, $instructor,$image,$attachments, $sent_by, $replacedBody, $subject)
                    );

                }

     }

        return ResponseHelper::success();
    }

    /**
     * change the status by the read parameter
     * @param \App\Http\Requests\AvailabilityRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function setAvailability(AvailabilityRequest $request)
    {
        $instructorId = $request->input('instructor_id');
        $courseId = $request->input('course_id');
        $classId = $request->input('class_id');
        $status = $request->input('status');
        $typeCourse = $request->input('type', 'official');

        $instructor = User::where('id',$instructorId)->first();
        $course = Course::where('id',$courseId)->first();
        $class = Classe::where('id', $classId)->first();
        $announcement = Announcement::where('user_id', $instructor->id)
        ->where('course_id', $courseId)
        ->where('classe_id', $classId)
        ->where('type', $typeCourse)
        ->first();

        $read = $announcement ? $announcement->read : true;
        if(!$read){
        $announcement->update([
            'available' => $status,
            'read'=> 1
        ]);
        }
            else
            $announcement->update([
                'available' => $status,
            ]);
        return ResponseHelper::success();

    }

    public function setAvailabilitybyInstructor(AvailabilityRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $instructorId = $request->input('instructor_id');
        $courseId = $request->input('course_id');
        $classId = $request->input('class_id');
        $status = $request->input('status');
        $typeCourse = $request->input('type', 'official');

        // $instructor = User::where('id',$instructorId)->first();
        $course = Course::where('id',$courseId)->first();
        $class = Classe::where('id', $classId)->first();

        $announcement = Announcement::where('user_id', $user->id)
        ->where('course_id', $courseId)
        ->where('classe_id', $classId)
        ->where('type', $typeCourse)
        ->first();

        $read = $announcement ? $announcement->read : true;
        if(!$read){
        $announcement->update([
            'available' => $status,
            'read'=>1
        ]);
        }
            else
            $announcement->update([
                'available' => $status,
            ]);
        return ResponseHelper::success();

    }

    public function getAvailableInstructors(Request $request){

        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);


        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $classId = $request->input('class_id');

        // Fetch available instructors for the specified class
        $availableInstructors = User::whereHas('announcements', function ($query) use ($classId) {
            $query->where('classe_id', $classId)
                  ->where('available', '1');
        })->get();

        // Return the result in a success response
        return ResponseHelper::success($availableInstructors);

    }


    public function getCourseAnnouncement(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role) {
            return ResponseHelper::authorizationFail(__('message.unauthorized'));
        }

        $query = QueryBuilder::for(Announcement::class)
            ->with(['user','classe','course','externalCourse'])
            ->allowedSorts(['name', 'created_at']);

        if ($role->name === 'admin' || $role->name === 'supervisor') {
            $query->allowedFilters(['classe_id','course_id','user_id','available','read',
                AllowedFilter::exact('type')]);
        } elseif ($role->name === 'trainer') {
            $query->where('user_id', $user->id)
                ->allowedFilters(['classe_id','course_id','available','read',
                AllowedFilter::exact('type')]);
        } else {
            return ResponseHelper::authorizationFail(__('message.unauthorized'));
        }
        if ($request->has('limit')) {
            $announcements = $query->paginate((int) $request->query('limit', 15))
                                ->appends($request->query());

            $collection = CourseAnnouncementResource::collection($announcements);
            $payload = $collection->response()->getData(true);

            return ResponseHelper::success($payload);
        } else {
            return ResponseHelper::success(
                CourseAnnouncementResource::collection($query->get())
            );
        }
    }

    public function getInstructorAnnouncement(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $query = QueryBuilder::for(Announcement::class)
            ->with(['user','classe','course'])
            ->allowedSorts(['name', 'created_at']);

            $query->where('user_id', $user->id)
                  ->allowedFilters(['classe_id','course_id','available','read']);

        $announcements = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        return ResponseHelper::success(CourseAnnouncementResource::collection($announcements));
    }

    public function destroy(Announcement $announcement)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $announcement->delete();
        return ResponseHelper::success();
    }
}
