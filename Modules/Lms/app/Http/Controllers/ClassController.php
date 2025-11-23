<?php

namespace Modules\Lms\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Models\ExternalCourse;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\TraineeResource;
use Modules\Lms\Enums\SessionStatusEnum;
use Modules\Lms\Transformers\ExternalScheduleResource;
use Modules\TrainerManagement\Models\TrainerSignature;
use Modules\Lms\Http\Requests\{ClassRequest ,CountTraineeRequest};
use Modules\Lms\Http\Resources\{ClassResource ,SessionResource ,ScheduleResource};
use Modules\Lms\Models\{Classe ,Course , ExternalSchedule, Schedule ,SessionCourse};

class ClassController extends Controller
{
    /**
     * Display a listing of the classes.
     *
     */

    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        $courseType = $request->input('filter.course_type', 'official');

        $query = QueryBuilder::for(Classe::class)
            ->allowedFilters([
                'title',
                'type',
                'course_id',
                'schedule_id',
                'course_type',
                AllowedFilter::callback('city', function ($query, $value) use ($courseType) {
                    $query->where(function ($q) use ($value, $courseType) {
                        if ($courseType === 'custom') {
                            $q->whereHas('externalSchedule', fn ($q) => $q->where('city_id', 'like', "%{$value}%"));
                        } else {
                            $q->whereHas('schedule', fn ($q) => $q->where('city_id', 'like', "%{$value}%"));
                        }
                    });
                }),
                AllowedFilter::callback('start_date', function ($query, $value) use ($courseType) {
                    $query->where(function ($q) use ($value, $courseType) {
                        if ($courseType === 'custom') {
                            $q->whereHas('externalSchedule', fn ($q) => $q->whereDate('start_date', $value));
                        } else {
                            $q->whereHas('schedule', fn ($q) => $q->whereDate('start_date', $value));
                        }
                    });
                }),
            ])
            ->allowedSorts(['title', 'type', 'created_at'])
            ->latest('created_at');
        // Check if the user is a trainee
        if ($role->name == 'trainee') {
            $query->whereHas('trainees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        } elseif ($role->name == 'companySupervisor') {
            // Check if the user is a company supervisor
            $companyId = $user->company_id;
            // Fetch classes belonging to the user's company
            $query->whereHas('trainees', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }


        // Check if the user is a trainer
        elseif ($role->name == 'trainer') {
            $query->where('trainer_id', $user->id);
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        // Paginate the results
        $classes = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'classes' => ClassResource::collection($classes)
        ];
        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $classes->total(),
                'per_page' => $classes->perPage(),
                'current_page' => $classes->currentPage(),
                'last_page' => $classes->lastPage(),
                'from' => $classes->firstItem(),
                'to' => $classes->lastItem(),
                'links' => [
                    'first' => $classes->url(1),
                    'last' => $classes->url($classes->lastPage()),
                    'prev' => $classes->previousPageUrl(),
                    'next' => $classes->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }


    // public function get_trainees(Request $request)
    // {
    //     $user = Auth::user();
    //     $currentRole = $user->current_role_id;
    //     $role = Role::find($currentRole);

    //     $classId = $request->input('class_id');
    //     $courseId = $request->input('course_id');
    //     $companyId = $request->input('company_id');
    //     $traineesQuery = User::query()->whereHas('roles', function ($query) {
    //         $query->where('name', 'trainee');
    //     });
    //     if ($role->name == 'trainee') {
    //         $traineesQuery->where('id', $user->id);
    //     } elseif ($role->name == 'companySupervisor') {
    //         $companyId = $companyId ?? $user->company_id;
    //         $traineesQuery->where('company_id', $companyId);
    //     } elseif ($role->name == 'trainer') {
    //         $traineesQuery->whereHas('classes', function ($query) use ($user) {
    //             $query->where('trainer_id', $user->id);
    //         });
    //     }
    //     if ($classId) {
    //         $traineesQuery->whereHas('trainees', function ($query) use ($classId) {
    //             $query->where('classes.id', $classId);
    //         });
    //     }
    //     if ($courseId) {
    //         $traineesQuery->whereHas('trainees.course', function ($query) use ($courseId) {
    //             $query->where('courses.id', $courseId);
    //         });
    //     }
    //     if ($companyId) {
    //         $traineesQuery->where('company_id', $companyId);
    //     }
    //     $trainees = $traineesQuery->get();
    //     $data = [
    //         'trainees' => UserResource::collection($trainees),
    //     ];

    //     return ResponseHelper::success($data);
    // }


    public function get_trainees(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $classId = $request->input('class_id');
        $courseId = $request->input('course_id');
        $companyId = $request->input('company_id');
        $searchName = $request->input('name');

        $traineesQuery = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'trainee'));

        $traineesQuery->when($role->name === 'trainee', fn ($q) => $q->where('users.id', $user->id))
            ->when($role->name === 'companySupervisor', function ($q) use ($user, $companyId) {
                $q->where('users.company_id', $companyId ?? $user->company_id);
            })
            ->when($role->name === 'trainer', function ($q) use ($user) {
                $q->whereHas('trainees', fn ($q) => $q->where('classes.trainer_id', $user->id));
            });

        $traineesQuery->when($classId, fn ($q) =>
            $q->whereHas('trainees', fn ($q) => $q->where('classes.id', $classId))
        );

        $traineesQuery->when($courseId, fn ($q) =>
            $q->whereHas('trainees.course', fn ($q) => $q->where('courses.id', $courseId))
        );

        $traineesQuery->when($companyId, fn ($q) =>
            $q->where('users.company_id', $companyId)
        );

        $traineesQuery->when($searchName, function ($q) use ($searchName) {
            $q->where(function ($query) use ($searchName) {
                $query->where('name', 'like', "%$searchName%");
            });
        });

        $trainees = $traineesQuery->with('deals.course')->get();

        return ResponseHelper::success([
            'trainees' => UserResource::collection($trainees),
        ]);
    }

    public function getTraineeById($id)
    {
        $trainee = User::whereHas('roles', fn($q) => $q->where('name', 'trainee'))
            ->with([
                'deals.course',     
                'deals.externalCourse',
                'company',           
                'currentRole',    
            ])
            ->findOrFail($id);

        return ResponseHelper::success([
            'trainee' => new TraineeResource($trainee),
        ]);
    }


    /**
     * Store a newly created class in storage.
     *
     */
    public function store(ClassRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $courseType = $request->input('course_type');
        $courseId = $request->input('course_id');
        $scheduleId = $request->input('schedule_id');

        $course = $courseType === 'custom'
        ? ExternalCourse::find($courseId)
        : Course::find($courseId);

        $schedule = $courseType === 'custom'
            ? ExternalSchedule::find($scheduleId)
            : Schedule::find($scheduleId);

        if (!$course) {
            return ResponseHelper::invalidData('Course not found', 404);
        }
        $class = Classe::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'trainer_id' => $request->input('trainer_id'),
            'course_id' => $courseId,
            'schedule_id' => $scheduleId,
            'course_type' => $courseType,
            'type' => $course->online,
        ]);

        if ($schedule) {
            $class->update([
                'type'       => $schedule->online,
                'trainer_id' => $schedule->trainer_id ?? $request->input('trainer_id'),
                'city'       => $schedule->city_id,
                'startDate'  => $schedule->start_date,
            ]);

        }

        if ($request->has('sync_content') && $course) {
            try {
                        Log::info('Sync content enabled', ['course_id' => $course->id]);

                // Retrieve the days_content array from the course
                $daysContent = is_array($course->days_content)
                                ? $course->days_content
                                : json_decode($course->days_content, true);

                                        Log::info('Decoded daysContent:', ['daysContent' => $daysContent]);

                // Ensure that $daysContent is an array
                if (empty($daysContent) || !is_array($daysContent)) {
                    Log::warning('Invalid or empty daysContent', [
                        'course_id' => $course->id,
                        'raw_days_content' => $course->days_content,
                        'parsed' => $daysContent
                    ]);
                    return ResponseHelper::invalidData('Invalid or missing course content. Please check the course.');

                }

                // Get the number of sessions based on the duration of the course
                $numSessions = $course->duration;

                // Define the list of holidays or excluded days
                $excludedDays = $request->has('excludedDays') ? $request->input('excludedDays') : ['Saturday', 'Sunday'];

                // Initialize the start date with the schedule's start date
                $currentStartDate = $schedule->start_date;

                // Create sessions
                $sessions = [];
                for ($i = 0; $i < $numSessions; $i++) {
                    // Check if the current start date falls on a holiday
                    while (in_array(Carbon::parse($currentStartDate)->format('l'), $excludedDays)) {
                        // If it does, move to the next day
                        $currentStartDate = Carbon::parse($currentStartDate)->addDay()->toDateString();
                    }

                    // Check if the array index exists before using it
                    $description = isset($daysContent[$i]) ? $daysContent[$i] : null;

                    // Create the session
                    $sessions[] = SessionCourse::create([
                        'title' => __('message.session') . ' ' . ($i + 1),
                        'description' => is_array($description) ? json_encode($description) : $description,
                        'startDate' => $currentStartDate,
                        'classe_id' => $class->id,
                        'status' => SessionStatusEnum::scheduled
                    ]);

                    // Move to the next day for the next session
                    $currentStartDate = Carbon::parse($currentStartDate)->addDay()->toDateString();
                }
            } catch (\Throwable $e) {
                Log::error('Exception in sync_content', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return ResponseHelper::invalidData('Invalid or missing course content. Please check the course.');
            }

        }

        return ResponseHelper::create(new ClassResource($class));

    }

    public function show(Classe $classe)
    {
        return ResponseHelper::success(new ClassResource($classe));
    }

    /**
     * Update the specified class in storage.
     *
     */
    public function update(ClassRequest $request, Classe $classe)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        // $classe->update($request->validated());
        $courseType = $request->input('course_type');
        $courseId = $request->input('course_id');
        $scheduleId = $request->input('schedule_id');

        $courseModel = $courseType === 'custom' ? ExternalCourse::class : Course::class;
        $scheduleModel = $courseType === 'custom' ? ExternalSchedule::class : Schedule::class;

        $course = $courseModel::find($courseId);
        $schedule = $scheduleModel::find($scheduleId);

        $classe->update([
            'schedule_id'  => $scheduleId,
            'course_id'    => $courseId,
            'course_type'  => $courseType,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'startDate' => $request->input('startDate'),
            'trainer_id' => $request->input('trainer_id'),
            'type' => $course->online
        ]);
        // Get the course associated with the class
        $schedule = Schedule::find($scheduleId);

        if ($schedule) {
            $classe->type = $schedule->online;
            $classe->trainer_id = $schedule->trainer_id ?? $request->input('trainer_id');
            $classe->city = $schedule->city_id;
            $classe->startDate = $schedule->start_date;
            $classe->save();
        }



        if ($request->has('sync_content') && $course) {
            // Retrieve the days_content array from the course
            $daysContent = is_array($course->days_content)
                            ? $course->days_content
                            : json_decode($course->days_content, true);

            // Get the number of sessions based on the duration of the course
            $numSessions = $course->duration;

            // Define the list of holidays or excluded days
            $excludedDays = $request->has('excludedDays') ? $request->input('excludedDays') : ['Saturday', 'Sunday'];

            // Initialize the start date with the schedule's start date
            $currentStartDate = $schedule->start_date;
            $classe->sessions()->delete();
            // Create sessions
            $sessions = [];
            for ($i = 0; $i < $numSessions; $i++) {
                // Check if the current start date falls on a holiday
                while (in_array(Carbon::parse($currentStartDate)->format('l'), $excludedDays)) {
                    // If it does, move to the next day
                    $currentStartDate = Carbon::parse($currentStartDate)->addDay()->toDateString();
                }
                $title = __('message.session') . ' ' . $i + 1;
                // Create the session
                $sessions[] = SessionCourse::create([
                    'description' => $daysContent[$i],
                    'title' => $title,
                    'startDate' => $currentStartDate,
                    'classe_id' => $classe->id,
                    'status' => SessionStatusEnum::scheduled
                ]);

                // Move to the next day for the next session
                $currentStartDate = Carbon::parse($currentStartDate)->addDay()->toDateString();
            }
        }

        return ResponseHelper::success(new ClassResource($classe));
    }

    /**
     * Remove the specified class from storage.
     *
     */
    public function destroy(Classe $classe)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $classe->delete();
        return ResponseHelper::success();
    }

    public function get_schedule_sessions(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // if (!in_array($role->name, ['admin', 'supervisor', 'trainer'])) {
        //     return ResponseHelper::authorizationFail();
        // }
        $sessions = SessionCourse::where('classe_id', $request->input('classe_id'))->get();
        // $schedules = $course->schedules()->get();

        return ResponseHelper::success(SessionResource::collection($sessions));
    }

    public function get_schedule_classes(Request $request)
    {
        $courseId = $request->input('course_id');
        $courseType = $request->input('course_type', 'official');

        if ($courseType === 'custom') {
            $course = ExternalCourse::with('schedules')->find($courseId);

            if (!$course) {
                return ResponseHelper::invalidData('Course not found', 404);
            }

            return ResponseHelper::success(ExternalScheduleResource::collection($course->schedules));
        }

        $course = Course::with('schedules')->find($courseId);

        if (!$course) {
            return ResponseHelper::invalidData('Course not found', 404);
        }

        return ResponseHelper::success(ScheduleResource::collection($course->schedules));
    }



    public function get_trainee_counts(CountTraineeRequest $request)
    {
        $classId = $request->class_id;

        $class = Classe::withCount('trainees')->find($classId);

        if (!$class) {
            return ResponseHelper::DataNotFound();
        }

        return ResponseHelper::success($class->trainees_count);
    }

 public function getClassAttendance(Request $request)
{
    $user = Auth::user();
    $currentRole = $user->current_role_id;
    $role = Role::find($currentRole);

    $classId = $request->input('class_id');
    $companyId = $request->input('company_id');
    $courseId = $request->input('course_id');
    $typeCourse = $request->input('course_type', 'official');
    $search = $request->input('search');

    $classQuery = Classe::with(['sessions.attendances', 'schedule']);

    if ($request->filled('class_id')) {
        $classQuery->where('id', $classId);
    }

    if ($request->filled('course_id')) {
        $classQuery->where('course_id', $courseId);
    }

    if (!empty($search)) {
        $classQuery->where('title', 'LIKE', "%{$search}%");
    }

    $class = $classQuery->where('course_type', $typeCourse)->first();

    if (!$class && ($request->filled('class_id') || $request->filled('course_id'))) {
        return ResponseHelper::DataNotFound('Class or Course not found');
    }

    $courseData = null;
    if ($class) {
        if ($class->course_type === 'custom') {
            $courseData = $class->externalCourse ?? null; 
        }else {
            $courseData = $class->course; 
        }
    }

       $trainer = null;
    if ($class->trainer_id) {
        $trainer = User::find($class->trainer_id);
        if ($trainer) {
            $trainer = $trainer->only(['id','name','email','phone', 'middel_name','last_name']);
        }
    }


    $traineesQuery = $class ? $class->trainees() : User::where('company_id', $companyId);

    if ($role->name == 'trainee') {
        $traineesQuery->where('id', $user->id);
    } elseif ($role->name == 'companySupervisor') {
        $companyId = $companyId ?? $user->company_id;
        $traineesQuery->where('company_id', $companyId);
    } elseif (!in_array($role->name, ['admin', 'supervisor', 'trainer'])) {
        return ResponseHelper::authorizationFail();
    }

    if ($request->filled('company_id')) {
        $traineesQuery->where('company_id', $companyId);
    }

    $students = $traineesQuery->with([
        'attendances' => function ($query) use ($class) {
            if ($class) {
                $query->whereIn('session_id', $class->sessions->pluck('id'));
            }
        }
    ])->get();

    $studentData = $students->map(function ($student) use ($class) {
        $attendances = $class ? $class->sessions->flatMap(function ($session) use ($student) {
            return $session->attendances->where('trainee_id', $student->id);
        }) : collect();

        return [
            'trainee' => $student->only(['id', 'name', 'email']),
            'company' => $student->company ? $student->company->only(['id', 'name']) : null,
            'attendance_count' => $attendances->where('status', 'present')->count(),
            'absence_count' => $attendances->where('status', 'absent')->count(),
            'tardiness_count' => $attendances->where('status', 'tardiness')->count(),
            'trainer_signature' => $class
                ? TrainerSignature::where('classe_id', $class->id)
                    ->where('trainee_id', $student->id)
                    ->value('signature')
                : null
        ];
    });

    $responseData = [
        'class' => $class,
        'course' => $courseData, 
        'trainer' => $trainer,
        'schedule' => $class?->schedule,
        'student_count' => $students->count(),
        'session_count' => $class?->sessions->count() ?? 0,
        'students' => $studentData,
    ];

    return ResponseHelper::success($responseData);
}
}

