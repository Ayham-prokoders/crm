<?php

namespace Modules\Lms\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Notifications\CertificateNotification;
use Modules\Lms\Http\Resources\CertificateResource;
use Modules\Lms\Http\Requests\{CertificateRequest,ShowInWebsiteRequest};
use Modules\Lms\Models\{Classe ,Course ,Company ,Certificate,ExternalCourse};

class CertificateController extends Controller
{

    public function index(Request $request)
    {
        $user=Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $query = QueryBuilder::for(Certificate::class)
        ->allowedFilters(['course_id','user_id','origin','course_type'])
        ->allowedSorts(['course_id', 'created_at'])
        ->orderBy('created_at','desc');

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
        }

        // Check the user's role
        if ($role->name=='trainee') {
            // If the user is a trainee, filter certificates by their user_id
            $query->where('user_id', $user->id);
        } elseif ($role->name=='trainer') {
            // If the user is a trainer, get the IDs of classes they are assigned to
            $classIds = $user->trainerClasses()->pluck('id')->toArray();
            // Filter certificates by class_id
            $query->whereIn('class_id', $classIds);
        }
        elseif ($role->name=='companySupervisor') {
            // If the user is a companySupervisor,
            $userCompany=User::where('company_id',$user->company_id)->get()->pluck('id');
            $query->whereIn('user_id',$userCompany);
        }

        elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $certificates = $request->input('limit')
        ? $query->withoutGlobalScope('project_source_l1')->paginate($request->input('limit'))
        : $query->withoutGlobalScope('project_source_l1')
            ->get();

    $data = [
        'certificates' => CertificateResource::collection($certificates),
    ];

    if ($request->input('limit')) {
        $data['pagination'] = [
            'total' => $certificates->total(),
            'per_page' => $certificates->perPage(),
            'current_page' => $certificates->currentPage(),
            'last_page' => $certificates->lastPage(),
            'from' => $certificates->firstItem(),
            'to' => $certificates->lastItem(),
            'links' => [
                'first' => $certificates->url(1),
                'last' => $certificates->url($certificates->lastPage()),
                'prev' => $certificates->previousPageUrl(),
                'next' => $certificates->nextPageUrl(),
            ],
        ];
    }

    return ResponseHelper::success($data);
    }

    public function store(CertificateRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }
        $courseType = $request->input('course_type');

        $class=Classe::find($request->input('class_id'));
        $course = $courseType === 'custom'
        ? ExternalCourse::where('id',$class->course_id)->first()
        : Course::where('id',$class->course_id)->first();

        // return($course);
        $user=User::find($request->input('user_id'));

        // Check if the user is a trainee in the class
        if (!$class->trainees->contains($user)) {
            return ResponseHelper::operationFail(__('message.not_trainee_in_class'));
        }
        // Check if the user already has a certificate for this course
        $existingCertificate = Certificate::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->where('course_type', $courseType)
        ->first();

        if ($existingCertificate) {
            return ResponseHelper::operationFail(__('message.user_obtained_certificate_for_this_course'));
        }
        Log::info('User first_name: ' . $user->name);

        $generatedID = Certificate::generateUniqueCertificateID($user->name);

        //check if certificate_id is unique
        // $certificate=Certificate::where('ID_certificate',$request->input('ID_certificate'))->first();
        // if($certificate){
        //     return ResponseHelper::operationFail(__('message.certificate_id_exist'));
        // }
        // Create a new certificate
        $certificate = Certificate::create([
        'course_custom_name'=>$course->name,
        'type'=>$class->type==0?'Classic':'Online',
        'course_type'=>$courseType,
        'course_id'=>$course->id,
        'first_name'=>$user->name ?? '',
        'middle_name'=>$user->middel_name ?? '',
        'last_name'=>$user->last_name ?? '',
        'ID_certificate' => $generatedID,
        'image'=>$request->input('image'),
        'pdf'=>$request->input('pdf'),
        'user_id'=>$request->input('user_id'),
        'show_in_website'=>$request->input('show_in_website'),
        'origin'=>'CRM',
        ]);
        $user->notify(new CertificateNotification($user->id,__('message.assigned_to_new_certificate')));

        // if($user->company_id){
        //     $roleCompanySupervisor=Role::where('name','companySupervisor')->first();
        //     if ($roleCompanySupervisor) {
        //     $companyAdmins=User::where('company_id',$user->company_id)
        //                         ->where('role',$roleCompanySupervisor->id)
        //                         ->get();

        //     foreach($companyAdmins as $admin)
        //         $admin->notify(new CertificateNotification($user->id));
        //     }
        // }
        if ($user->company_id) {
            $company = Company::find($user->company_id);

            if ($company) {
                $companySupervisors = $company->supervisors;
                foreach ($companySupervisors as $supervisor) {
                    $supervisor->notify(new CertificateNotification($user->id,__('message.your_trainees_has_certificate')));
                }
            }
        }

        // Return the created certificate resource
        return ResponseHelper::create(new CertificateResource($certificate));
    }

    public function generateCertificateID()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->currentRole->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        do {
            $certificateID = strtoupper(Str::random(12));
        } while (Certificate::where('ID_certificate', $certificateID)->exists());

        return ResponseHelper::success([
            'status' => 'success',
            'certificateID' => $certificateID,
        ]);
    }

    public function show(Certificate $certificate)
    {
        // Return the certificate resource
        return ResponseHelper::success(new CertificateResource($certificate));
    }

    public function update(CertificateRequest $request, $id)
    {
        $authUser = Auth::user();
        $currentRole = $authUser->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        $certificate = Certificate::withoutGlobalScopes()->find($id);

        if (!$certificate) {
            return ResponseHelper::DataNotFound('Certificate not found');
        }
        $courseType = $request->input('course_type');

        $class = Classe::findOrFail($request->input('class_id'));
        $course = $courseType === 'custom'
        ? ExternalCourse::findOrFail($class->course_id)
        : Course::findOrFail($class->course_id);

        $newUser = User::findOrFail($request->input('user_id'));

        // التأكد أن المستخدم الجديد متدرب في الكلاس
        if (!$class->trainees->contains($newUser)) {
            return ResponseHelper::operationFail(__('message.not_trainee_in_class'));
        }

        // إذا تم تغيير المستخدم، نتحقق إذا عنده شهادة مسبقة لنفس الكورس
        $isUserChanged = $newUser->id !== $certificate->user_id;

        if ($isUserChanged) {
            $existingCertificate = Certificate::where('user_id', $newUser->id)
                ->where('course_id', $course->id)
                ->where('course_type', $courseType)
                ->first();

            if ($existingCertificate) {
                return ResponseHelper::operationFail(__('message.user_obtained_certificate_for_this_course'));
            }

            // توليد ID جديد بناءً على اسم المستخدم الجديد
            $newCertificateID = Certificate::generateCertificateID($newUser->name, $newUser->last_name);
        }

        $certificate->update([
            'course_custom_name' => $course->name,
            'type' => $class->type == 0 ? 'Classic' : 'Online',
            'course_type'=>$courseType,
            'course_id' => $course->id,
            'first_name' => $newUser->name,
            'middle_name' => $newUser->middel_name,
            'last_name' => $newUser->last_name,
            'ID_certificate' => $isUserChanged ? $newCertificateID : $certificate->ID_certificate,
            'image' => $request->input('image'),
            'pdf' => $request->input('pdf'),
            'show_in_website' => $request->input('show_in_website', $certificate->show_in_website),
            'user_id' => $newUser->id,
            'origin' => 'CRM',
        ]);

        return ResponseHelper::success(new CertificateResource($certificate));
    }


    public function destroy($id)
    {

        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }
        // Delete the certificate
        $certificate = Certificate::withoutGlobalScopes()->find($id);
    if (!$certificate) {
        return ResponseHelper::DataNotFound('Certificate not found');
    }
        $certificate->delete();

        // Return a success response
        return ResponseHelper::success('Certificate deleted successfully');
    }

    public function showInWebsite(ShowInWebsiteRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $certificateIds = $request->input('certificates');

        Certificate::whereIn('id', $certificateIds)
            ->update(['show_in_website' => true]);
        return ResponseHelper::success();
    }


    public function getCertificate(Request $request)
    {

        $data=Certificate::with(['user','course'])
        ->where('origin','crm')
        ->where('show_in_website',true)
        ->get();
        return ResponseHelper::success($data);
    }

}
