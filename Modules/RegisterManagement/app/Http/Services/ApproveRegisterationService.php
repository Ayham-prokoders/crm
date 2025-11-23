<?php

namespace Modules\RegisterManagement\Http\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\UserCreatedMail;
use App\Services\LmsSyncService;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Events\UserAssignedToClass;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Lms\Models\{Company, Classe, City, Schedule, Course};

class ApproveRegisterationService
{
    public function approve($registration, $data)
    {
        DB::beginTransaction();
        try {
            $messages = [];

            if ($registration['type'] === 'register-form') {
                $messages = $this->processSingleRegistration($registration, $data);
            } elseif ($registration['type'] === 'multiple-register-form') {
                $messages = $this->processMultipleRegistration($registration, $data);
            }

            DB::commit();

            return $messages;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration approval error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processSingleRegistration($registration, $data)
    {
        $messages = [];
        $classe = $this->getClass($registration);
        if (!$classe) {
            $messages[] = "No class found for this registration.";
            return $messages;
        }
        $existingUser = User::where('email', $registration['email'])
            // ->where('name', $registration['full_name'])
            ->first();

        if ($existingUser && $classe->trainees()->where('user_id', $existingUser->id)->exists()) {
            $messages[] = "The participant '{$registration['full_name']}' with email '{$registration['email']}' is already registered in this course.";
            return $messages;
        }
        if (!$existingUser) {
            $password = Str::random(12);
            $user = User::create([
                'name' => $registration['full_name'],
                'email' => $registration['email'],
                'password' => bcrypt($password),
                'mobile' => $registration['mobile'],
                'nationality' => $registration['nationality'],
                'salutation' => $registration['salutation'],
            ]);

            $role = Role::where('name', 'trainee')->first();
            $user->assignRole($role);
            $user->update(['role' => $role->id, 'current_role_id' => $role->id]);
            LmsSyncService::sync($user, 'update');
            // Send an email notification
            try {
                Mail::to($user->email)->queue(new UserCreatedMail($user, $password, null));
            } catch (\Exception $e) {
                Log::error('Mail error: ' . $e->getMessage());
            }
        }
        else {
            $user = $existingUser;
        }
        if ($classe) {
            $classe->trainees()->attach($user->id);
            $classId = $classe->id;
            dispatch(function () use ($user, $classId) {
                LmsSyncService::syncTraineeClasses($user, $classId);
            })->afterResponse();
            // Trigger the event to send notification
            try {
                event(new UserAssignedToClass($user));
            } catch (\Exception $e) {
                Log::error('خطأ في الحدث: ' . $e->getMessage());
            }
        }
        return $messages;
    }

    private function processMultipleRegistration($registration, $data)
    {
        $classe = $this->getClass($registration);
        $messages = [];

        if (!$classe) {
            $messages[] = "No class found for this registration.";
            return $messages;
        }
        $company = Company::firstOrCreate([
            'email' => $registration['email']
        ], [
            'name' => $registration['company'],
            'bill_email' => $data['bill_email'] ?? $registration['email'],
        ]);

        foreach ($registration['participants'] as $participant) {
            $email = $participant['email'] ?? null;
            $name = $participant['name'] ?? 'Unknown';

            $existingUser = $email ? User::where('email', $email)
            // ->where('name', $name)
            ->first() : null;

            if ($existingUser && $classe->trainees()->where('user_id', $existingUser->id)->exists()) {
                $messages[] = "The participant '{$name}' with email '{$email}' is already registered in this course.";
                continue;
            }

            // $user = $existingUser;
            if (!$existingUser) {
                $password = Str::random(12);
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'phone' => $participant['phone'] ?? null,
                    'company_id' => $company->id,
                    'nationality' => $registration['nationality'],
                    // 'position' => $participant['position'] ?? null,
                ]);

                $role = Role::where('name', 'trainee')->first();
                $user->assignRole($role);
                $user->update(['role' => $role->id, 'current_role_id' => $role->id]);
                if ($email) {
                    try {
                        Mail::to($user->email)->queue(new UserCreatedMail($user, $password, null));
                    } catch (\Exception $e) {
                        Log::error('Mail error: ' . $e->getMessage());
                    }
                }
            } else {
                $user = $existingUser;
                $user->update(['company_id' => $company->id]);
                LmsSyncService::sync($user, 'update');
            }

            if ($classe) {
                $classe->trainees()->attach($user->id);
                $classId = $classe->id;
                dispatch(function () use ($user, $classId) {
                    LmsSyncService::syncTraineeClasses($user, $classId);
                })->afterResponse();
            }
            try {
                event(new UserAssignedToClass($user));
            } catch (\Exception $e) {
                Log::error('Event error: ' . $e->getMessage());
            }
        }
        return $messages;
    }

    private function getClass($registration)
    {
        \Log::info('getClass', ['data' => $registration]);
          //for new courses
        // $projectSource = strlen($registration['course_code']) >= 5
        //     ? substr($registration['course_code'], 3, 2)
        //     : 'L1';
        $projectSource = 'L1';

        $city = strtolower($registration['course_city']) !== 'online'
            ? City::where('name', $registration['course_city'])
            ->where('project_source', $projectSource)
            ->first()
            : null;


        // if ($city === null && strtolower($registration['course_city']) !== 'online') {
        //     return null;
        // }

        \Log::info('city:', ['city' => $city]);

        try {
            $dateString = trim($registration['course_date']);
            $parsedDate = \DateTime::createFromFormat('Y-m-d', $dateString);

            if (!$parsedDate) {
                \Log::error('date : ' . $dateString);
                return null;
            }

            $courseDate = Carbon::instance($parsedDate)->format('Y-m-d');
        } catch (\Exception $e) {
            \Log::error('failed create course', [
                'input' => $registration['course_date'],
                'exception' => $e->getMessage()
            ]);
            return null;
        }       // $courseDate = $registration['course_date'];


        $course = Course::where('external_id', $registration['course_id'])
            ->where('project_source', $projectSource)
            ->first();

        if (!$course) {
            \Log::warning('there is no course');
            return null;
        }
            \Log::info('there is course'.$course->id);

        $schedule = Schedule::where('start_date', $courseDate)
            ->where('course_id', $course->id)
            ->when($city, function ($query) use ($city, $projectSource) {
                return $query->where('city_id', $city->id)
                    ->where('project_source', $projectSource);
            }, function ($query) {
                return $query->whereNull('city_id');
            })
            ->first();

        \Log::info('schedule:', ['schedule' => $schedule]);

        if (!$schedule) {
            \Log::warning('there is no scedule');

            return null;
        }
        $classe = Classe::where('course_id', $course->id)
            ->where('schedule_id', $schedule->id)
            ->first();

        \Log::info('class already exists', ['classe' => $classe]);

        if (!$classe) {
            \Log::info('creating class');

            $classe = Classe::create([
                'course_id' => $course->id,
                'schedule_id' => $schedule->id,
                'startDate' => $schedule->start_date,
                'city' => $city ? $city->id : null,
                'title' => $registration['course_name'] . $registration['course_date'] . ($city ? $city->name : 'Online'),
                'type' => $city ? 'classic' : 'online',
            ]);
            \Log::info('class created successfully: ', ['classe' => $classe]);

        }
        return $classe;
    }
}
