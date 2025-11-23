<?php

namespace App\Http\Controllers\Api;

use App\Models\AnswerdForm;
use App\Models\Attendance;
use Modules\Lms\Models\{Classe ,Certificate ,Course};
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
class ReportController extends Controller
{
    //No of Employees under the same account name
    public function employeesUnderAccount($companyId)
    {
        $employees = User::where('company_id', $companyId)
            ->with(['trainees.course.category'])
            ->get();

        $totalEmployees = $employees->count();

        $courses = [];
        $employees->each(function ($employee) use (&$courses) {
            $employee->trainees->each(function ($classe) use (&$courses, $employee) {
                $courseId = $classe->course->id;

                if (!isset($courses[$courseId])) {
                    $courses[$courseId] = [
                        'course_id' => $courseId,
                        'course_name' => $classe->course->name,
                        'category_id' => $classe->course->category->id,
                        'category_name' => $classe->course->category->title,
                        'employee_count' => 0,
                        'employees' => [],
                    ];
                }

                if (!in_array($employee->id, array_column($courses[$courseId]['employees'], 'employee_id'))) {
                    $courses[$courseId]['employees'][] = [
                        'employee' => $employee->makeHidden(['trainees']),
                        // 'employee_name' => $employee->name,
                        // 'employee_email' => $employee->email,
                    ];
                }

                $courses[$courseId]['employee_count']++;
            });
        });

        $response = [
            'total_employees' => $totalEmployees,
            'employess' => $employees,
            'course_employee' => array_values($courses),
        ];

        return ResponseHelper::success($response);
    }




    //No. of attended courses.
    public function attendedCourses(Request $request, $courseId)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $classesQuery = Classe::with(['course', 'trainees']);

        if (in_array($role->name, ['admin', 'supervisor'])) {
            $classesQuery->where('course_id', $courseId);
        } elseif ($role->name === 'companySupervisor') {
            $classesQuery->where('course_id', $courseId)
                ->whereHas('trainees', function ($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                });
        } elseif ($role->name === 'trainer') {
            $classesQuery->where('course_id', $courseId)
                        ->where('trainer_id', $user->id);
        } else {
            return ResponseHelper::authorizationFail();
        }

        if ($request->filled('city')) {
            $classesQuery->whereHas('schedule', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            });
        }

        $classes = $classesQuery->get();

        $data = $classes->map(function ($classe) use ($role, $user) {
            $trainees = $classe->trainees;

            if ($role->name === 'companySupervisor') {
                $trainees = $trainees->where('company_id', $user->company_id);
            }

            return [
                'course' => $classe->course,
                'category' =>$classe->course->category,
                'schedule' => $classe->schedule,
                'class' => $classe,
                'trainee_count' => $trainees->count(),
            ];
        });

        return ResponseHelper::success(['attended_courses' => $data]);
    }


    //Total Hrs Attended.
    public function totalHoursAttended($userId)
    {
        $classes = Classe::with(['course', 'sessions', 'course.category'])
            ->whereHas('trainees', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();

        $data = $classes->map(function ($classe) {
            $totalHours = $classe->sessions->sum('duration');
            return [
                // 'course' => $classe->course,
                'class' => $classe,
                'total_hours' => $totalHours,
            ];
        });

        return ResponseHelper::success(['classes_hours' => $data]);
    }

    //No. of certificates obtained. with quiz taken
    public function certificatesObtained($userId)
    {
        $certificates = Certificate::where('user_id', $userId)
            ->with('course')
            ->get();
        $quizzesCount = AnswerdForm::where('user_id', $userId)->count();
        $data = [
            'quizzes_taken' => $quizzesCount,
            'certificates_count' => $certificates->count(),
            'certificates' => $certificates->map(function ($certificate) {
                return [
                    'certificate' => $certificate,
                    'course' => $certificate->course,
                ];
            }),
        ];

        return ResponseHelper::success($data);
    }


    //No. of certificates obtained. by course
    public function certificatesObtainedByCourse($courseId)
{
    $user = Auth::user();
    $currentRole = $user->current_role_id;
    $role = Role::find($currentRole);

    $certificatesQuery = Certificate::where('course_id', $courseId)
        ->with(['user', 'course.category']);

    if (in_array($role->name, ['admin', 'supervisor'])) {
    } elseif ($role->name === 'companySupervisor') {
        $certificatesQuery->whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        });
    } elseif ($role->name === 'trainer') {
        $certificatesQuery->whereHas('course.classes.schedule', function ($query) use ($user) {
            $query->where('trainer_id', $user->id);
        });
    } else {
        return ResponseHelper::authorizationFail();
    }

    $certificates = $certificatesQuery->get();
    $data = [
        'certificates_count' => $certificates->count(),
        'certificates' => $certificates->map(function ($certificate) {
            $isCrmOrigin = $certificate->origin == 'crm';
            return [
                'certificate' => $certificate,
                'course' => $certificate->course,
                'category' => $certificate->course->category,
                'user_id' => $isCrmOrigin ? $certificate->user->id : $certificate->user_id,
                'email' => $isCrmOrigin ? $certificate->user->email : $certificate->email,
                'name' => $isCrmOrigin ? $certificate->user->name : trim($certificate->first_name . ' ' . $certificate->last_name),
            ];
        }),
    ];

    return ResponseHelper::success($data);
}

    //How many quizzes he/she took.
    public function quizzesTaken($userId)
    {
        $quizzesCount = AnswerdForm::where('user_id', $userId)->count();
        return ResponseHelper::success(['quizzes_taken' => $quizzesCount]);
    }

    //The results before/ after each course.
    public function getCoursesEvaluations(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $classId = $request->get('class_id');

        $formsQuery = AnswerdForm::whereHas('designedForm', function ($query) {
            $query->whereIn('type', ['pre_course', 'after_course']);
        })
        ->where('answered', true)
        ->with(['user', 'designedForm.classe.course.category']);

        if ($role->name == 'trainee') {
            $formsQuery->where('user_id', $user->id);
        } elseif ($role->name == 'companySupervisor') {
            $formsQuery->whereHas('user', function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            });
        } elseif ($role->name == 'trainer') {
            $formsQuery->whereHas('designedForm.classe', function ($query) use ($user) {
                $query->where('trainer_id', $user->id);
            });
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        if ($request->filled('class_id')) {
            $formsQuery->whereHas('designedForm.classe', function ($query) use ($classId) {
                $query->where('id', $classId);
            });
        }

        $forms = $formsQuery->get()->groupBy([
            fn($form) => $form->designedForm->classe->id,
            fn($form) => $form->user->id,
        ]);

        $result = $forms->map(function ($users, $classId) {
            $class = $users->first()->first()->designedForm->classe;
            return [
                'class_id' => $classId,
                'class_name' => $class->title,
                'course_name' => $class->course->name,
                'category_name' => $class->course->category->title,
                'users' => $users->map(function ($forms, $userId) {
                    $user = $forms->first()->user;
                    return [
                        'user_id' => $userId,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'forms' => $forms->map(function ($form) {
                            return [
                                'id' => $form->id,
                                'type' => $form->designedForm->type,
                                'rate' => $form->rate,
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        return ResponseHelper::success($result);
    }


    //Which city attended the courses,
    public function courseCities($userId)
    {
        $classes = Classe::with('schedule', 'schedule.city', 'course', 'course.category')
            ->whereHas('trainees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
        $citiesWithClasses = [];

        foreach ($classes as $class) {
            $city = $class->schedule->city->name;
            $city_id = $class->schedule->city_id;
            $citiesWithClasses[$city_id][] = [
                'class_id' => $class->id,
                'class_name' => $class->title,
                'course_name' => $class->course->name,
                'course_id' => $class->course->id,
                'category_id' => $class->course->category->id,
                'category_name' => $class->course->category->title,
                'schedule_id' => $class->schedule->id,
            ];
        }
        $result = [];
        foreach ($citiesWithClasses as $city_id => $classes) {
            $result[] = [
                'city' => $city,
                'classes' => $classes,
            ];
        }

        return ResponseHelper::success(['cities_attended' => $result]);
    }


    //Ratings based on his attendance by course or day
    public function ratingsBasedOnAttendance(Request $request)
    {
        $courseId = $request->input('course_id');
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : null;

        $classesQuery = Classe::with([
            'sessions' => function ($query) use ($date) {
                if ($date) {
                    $query->whereDate('startDate', $date);
                }
                $query->with('attendances');
            },
            'course'
        ])
        ->whereNotNull('course_id')
        ->whereHas('course');

        if ($courseId) {
            $classesQuery->where('course_id', $courseId);
        }

        $classes = $classesQuery->get();

        $courses = $classes->groupBy('course_id')->map(function ($courseClasses, $groupedCourseId) {
            $firstClass = $courseClasses->first();
            if (! $firstClass || ! $firstClass->course) {
                return null;
            }
            $course = $firstClass->course;

            $classData = $courseClasses->map(function ($class) {
                $attendances = $class->sessions->flatMap(function ($session) {
                    return $session->attendances;
                });

                $attendanceStats = $attendances->groupBy('status')->map(function ($group) {
                    return $group->count();
                });

                $totalStudents = $attendances->count();
                $attendanceRate = $totalStudents > 0
                    ? round(($attendanceStats->get('present', 0) / $totalStudents) * 100, 2)
                    : 0;

                return [
                    'class_id' => $class->id,
                    'class_title' => $class->title,
                    'attendance_count' => $attendanceStats->get('present', 0),
                    'absence_count' => $attendanceStats->get('absent', 0),
                    'tardiness_count' => $attendanceStats->get('tardiness', 0),
                    'total_students' => $totalStudents,
                    'attendance_rate' => $attendanceRate,
                ];
            });

            return [
                'course_id'   => $groupedCourseId,
                'course_name' => $course->name,
                'category' => $course->category,
                'classes' => $classData,
            ];
        })
        ->filter()
        ->values();

        $responseData = [
            'courses' => $courses,
            'total_courses' => $courses->count(),
        ];

        return ResponseHelper::success($responseData);
    }

    //No. of trainees per period ( Month/ year/ week)

    public function traineesPerPeriod(Request $request)
    {
        $start_date = $request->input('start_date');
        $start_date = !empty($start_date) ? Carbon::parse($start_date) : Carbon::now();

        $period = $request->input('period', 'month');
        if (empty($period)) {
            $period = 'month';
        }

        switch ($period) {
            case 'month':
                $end_date = $start_date->copy()->addMonth();
                break;
            case 'week':
                $end_date = $start_date->copy()->addWeek();
                break;
            case 'year':
                $end_date = $start_date->copy()->addYear();
                break;
            default:
                throw new \InvalidArgumentException('Invalid period specified.');
        }

        $classes = Classe::whereHas('schedule', function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        })
        ->with(['course:id,name,category_id','course.category:id,title,type','schedule:id,start_date,city_id','schedule.city:id,name'])
        ->withCount('trainees')
        ->get(['id', 'course_id', 'title', 'schedule_id']);

        $traineesPerCourse = $classes->groupBy('course_id')->map(function ($courseClasses) {
            return [
                'course_id' => $courseClasses->first()->course_id,
                'course_name' => $courseClasses->first()->course->name,
                'category_id' => $courseClasses->first()->course->category_id,
                'category_name' => $courseClasses->first()->course->category->type??null,
                'trainees_count' => $courseClasses->sum('trainees_count'),
                'classes' => $courseClasses->map(function ($classe) {
                    return [
                        'class_id' => $classe->id,
                        'class_name' => $classe->title,
                        'schedule' => $classe->schedule->start_date,
                        'trainees_count' => $classe->trainees_count,
                        'city' => $classe->schedule->city??null,
                    ];
                })->values(),
            ];
        })->values();

        $mostPopularCourse = $traineesPerCourse->sortByDesc('trainees_count')->first();

        $response = [
            'trainees_per_course' => $traineesPerCourse,
            'most_popular_course' => $mostPopularCourse,
        ];

        return ResponseHelper::success($response);
    }







    //No of courses are taken.
    public function coursesTaken($userId)
    {
        $coursesTaken = Classe::with(['course'])
            ->whereHas('trainees', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();
        $courses_count = $coursesTaken->count();

        return ResponseHelper::success([
            'courses_count' => $courses_count,
            'courses_taken' => $coursesTaken
        ]);
    }

    // Best reviewed courses.
    public function bestReviewedCourses(Request $request)
    {
        $limit = $request->query('limit', 5);

        $bestCourses = Course::with('feedbacks')
            ->withCount([
                'feedbacks as average_rating' => function ($query) {
                    $query->selectRaw('AVG((rate + trainers_rate + materials_rate + hospitality_rate + hotel_rate) / 5)');
                }
            ])
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'category_name' => $course->category->type ?? '',
                    'average_rating' => $course->average_rating,
                    'feedback_count' => $course->feedbacks->count(),
                ];
            });

        return ResponseHelper::success([
            'best_reviewed_courses' => $bestCourses
        ]);
    }


    //Total training hrs.
    public function totalTrainingHours($userId)
    {
        $classes = Classe::whereHas('trainees', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->with([
                    'sessions.attendances' => function ($query) use ($userId) {
                        $query->where('trainee_id', $userId)->where('status', 'present');
                    },
                    'sessions'
                ])->get();
        $data = $classes->map(function ($class) use ($userId) {
            $totalHours = $class->sessions->sum(function ($session) use ($userId) {
                $attendance = $session->attendances->firstWhere('trainee_id', $userId);
                return $attendance ? $session->duration : 0;
            });

            return [
                'class' => $class,
                'course_id' => $class->course->id,
                'course_name' => $class->course->name,
                'category_id' => $class->course->category->id,
                'category_name' => $class->course->category->title,
                'total_training_hours' => $totalHours,
            ];
        });

        return ResponseHelper::success([
            'training_hours_per_class' => $data
        ]);
    }
    ///////////
    //Advanced Reports

    //Session for all courses
    public function getSessionsReport(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $coursesQuery = Course::query()->with([
            'classes.sessions' => function ($query) {
                $query->where('status', 'completed');
            }
        ]);

        if ($role->name == 'trainee') {
            $coursesQuery->whereHas('classes.trainees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        } elseif ($role->name == 'companySupervisor') {
            $coursesQuery->whereHas('classes.trainees', function ($query) use ($user) {
                $query->whereHas('company', function ($q) use ($user) {
                    $q->where('id', $user->company_id);
                });
            });
        } elseif ($role->name == 'trainer') {
            $coursesQuery->whereHas('classes', function ($query) use ($user) {
                $query->where('trainer_id', $user->id);
            });
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $coursesQuery->when($request->city, function ($query) use ($request) {
            $query->whereHas('classes.schedule', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            });
        })->when($request->category, function ($query) use ($request) {
            $query->where('category_id', $request->category);
        });

        $courses = $coursesQuery->get();

        return ResponseHelper::success($courses);
    }

    //sessions for specific course
    public function getCourseReport(Request $request, $courseId)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        $course = Course::with([
            'category',
            'classes.sessions.attendances',
            'classes.schedule.city',
            'classes.trainer'
        ])->where('id', $courseId)->firstOrFail();

        $report = [];

        $courseData = [
            'id' => $course->id,
            'title' => $course->name,
            'description' => $course->description,
            'category' => $course->category ? [
                'id' => $course->category->id,
                'name' => $course->category->type,
            ] : null,
            'total_classes' => $course->classes->count(),
        ];

        foreach ($course->classes as $class) {
            $completedSessions = $class->sessions->filter(function ($session) {
                return $session->status === 'completed';
            });

            $totalSessions = $class->sessions->count();
            $completedCount = $completedSessions->count();
            $remainingCount = $totalSessions - $completedCount;
            $completionPercentage = $totalSessions > 0 ? ($completedCount / $totalSessions) * 100 : 0;

            $classReport = [
                'class' => [
                    'id' => $class->id,
                    'title' => $class->title,
                ],
                'city' => $class->schedule->city,
                'start_date' => $class->startDate,
                'instructor' => $class->trainer ? $class->trainer->only(['id', 'name', 'email']) : null,
                'total_sessions' => $totalSessions,
                'completed_sessions' => $completedSessions->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'title' => $session->title,
                    ];
                })->values(),
                'completion_percentage' => round($completionPercentage, 2),
                'remaining_sessions_count' => $remainingCount,
            ];

            switch ($role->name) {
                case 'trainee':
                    $classReport['attendance'] = $class->sessions->map(function ($session) use ($user) {
                        $attendance = $session->attendances->firstWhere('trainee_id', $user->id);
                        return [
                            'session_id' => $session->id,
                            'session_title' => $session->title,
                            'attendance_status' => $attendance ? $attendance->status : 'not_attended',
                        ];
                    })->values();
                    break;

                case 'companySupervisor':
                    $classReport['company_attendance'] = $class->sessions->map(function ($session) use ($user) {
                        return [
                            'session_id' => $session->id,
                            'session_title' => $session->title,
                            'attendances' => $session->attendances->where('company_id', $user->company_id)->map(function ($attendance) {
                                return [
                                    'trainee_id' => $attendance->trainee_id,
                                    'status' => $attendance->status,
                                ];
                            })->values(),
                        ];
                    })->values();
                    break;

                case 'trainer':
                    $classReport['sessions_summary'] = $class->sessions->map(function ($session) {
                        return [
                            'id' => $session->id,
                            'title' => $session->title,
                            'status' => $session->status,
                            'attendees_count' => $session->attendances->count(),
                        ];
                    })->values();
                    break;

                case 'admin':
                case 'supervisor':
                    break;

                default:
                    return ResponseHelper::authorizationFail();
            }

            $report[] = $classReport;
        }

        return ResponseHelper::success([
            'course' => $courseData,
            'classes_report' => $report,
        ]);
    }


    //courses by team or individual
    public function getCoursesReportByIndividualOrTeam(Request $request)
    {
        $query = Course::with(['classes.trainees']);

        if ($request->filled('course_id')) {
            $query->where('id', $request->get('course_id'));
        }

        $courses = $query->get();

        $result = [];
        foreach ($courses as $course) {
            foreach ($course->classes as $class) {
                $traineesWithoutCompany = [];
                $traineesGroupedByCompany = [];

                foreach ($class->trainees as $trainee) {
                    if (is_null($trainee->company_id)) {
                        $traineesWithoutCompany[] = [
                            'id' => $trainee->id,
                            'name' => $trainee->name,
                        ];
                    } else {
                        $categoryId = $course->category_id ?? 'Uncategorized';
                        $companyId = $trainee->company_id;

                        if (!isset($traineesGroupedByCompany[$companyId])) {
                            $traineesGroupedByCompany[$companyId] = [
                                'company_id' => $companyId,
                                'company_name' => $trainee->company->name,
                                'categories' => [],
                            ];
                        }

                        if (!isset($traineesGroupedByCompany[$companyId]['categories'][$categoryId])) {
                            $traineesGroupedByCompany[$companyId]['categories'][$categoryId] = [
                                'category_id' => $categoryId,
                                'category_name' => $course->category->type,
                                'trainees' => [],
                            ];
                        }

                        $traineesGroupedByCompany[$companyId]['categories'][$categoryId]['trainees'][] = [
                            'id' => $trainee->id,
                            'name' => $trainee->name,
                        ];
                    }
                }

                foreach ($traineesGroupedByCompany as &$companyGroup) {
                    $companyGroup['categories'] = array_values($companyGroup['categories']);

                    foreach ($companyGroup['categories'] as &$category) {
                        $category['trainees'] = array_values($category['trainees']);
                    }
                }

                $result[] = [
                    'class_id' => $class->id,
                    'class_title' => $class->title,
                    'course_id' =>$class->course->id,
                    'course_name' => $class->course->name,
                    'category_id' =>$class->course->category->id?? null,
                    'category_name' => $class->course->category->type?? null,
                    'schedule' =>$class->schedule?? null,
                    'city' => $class->schedule->city ?? null,
                    'trainees_without_company_count' => count($traineesWithoutCompany),
                    'trainees_without_company' => $traineesWithoutCompany,
                    'trainees_grouped_by_company_count' => count($traineesGroupedByCompany),
                    'trainees_grouped_by_company' => array_values($traineesGroupedByCompany),
                ];
            }
        }

        return ResponseHelper::success($result);
    }



}
