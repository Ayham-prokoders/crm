<?php
namespace App\Services;

use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use App\Models\FailedSync;
use App\Models\DesignedForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class LmsSyncService
{
    /**
     * connect to the lms database and insert the data in it
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $operation
     * @return void
     */
    public static function sync(Model $model, string $operation): void
    {
        $lmsConnection = 'lms_db';
        $modelClass    = get_class($model);
        $tableName     = config('lms_sync.models.' . $modelClass);

        Log ::info("🔄 [LMS SYNC] Starting sync", [
            'model' => $modelClass,
            'operation' => $operation,
            'table' => $tableName,
            'attributes' => $model->getAttributes(),
        ]);
        if (! $tableName) {
            Log::warning("Model not found : $modelClass");
            FailedSync::create([
                'model' => $modelClass,
                'operation' => $operation,
                'data' => json_encode($model->getAttributes()),
                'error_message' => 'No table mapping in lms_sync config',
            ]);
            return;
        }

        // $data = $model->toArray();
        // to get the columns only
        $data = self::normalizeAttributes($model->getAttributes());


        $data['created_at'] = optional($model->created_at)->format('Y-m-d H:i:s');
        $data['updated_at'] = optional($model->updated_at)->format('Y-m-d H:i:s');

        try {
            if ($operation === 'create') {
                $exists = DB::connection($lmsConnection)->table($tableName)
                ->where('id', $model->id)
                ->exists();
        
                if (! $exists) {
                    DB::connection($lmsConnection)->table($tableName)->insert($data);
                }
            } elseif ($operation === 'update') {
                DB::connection($lmsConnection)->table($tableName)
                    ->where('id', $model->id)
                    ->update($data);
            } elseif ($operation === 'delete') {
                DB::connection($lmsConnection)->table($tableName)
                    ->where('id', $model->id)
                    ->delete();
            }

            // Special sync for Note recipients
            if ($modelClass === Note::class) {

                Log::info($modelClass);
                self::syncNoteRecipients($model);
            }
            // Special sync for Instructor topics
            if ($tableName === 'instructors') {
                self::syncInstructorTopics($model);
            }

        } catch (\Exception $e) {
            FailedSync::create([
                'model'         => $modelClass,
                'operation'     => $operation,
                'data'          => json_encode($data),
                'error_message' => $e->getMessage(),
            ]);
            Log::error("LMS Sync Failed: {$operation} for $modelClass - " . $e->getMessage());
        }
    }

    /**
     * sync the note's relation (recptients) to make the oporation completed in the lms system
     * @param \Illuminate\Database\Eloquent\Model $note
     * @return void
     */
    protected static function syncNoteRecipients(Model $note)
    {
        try {
            $lmsConnection = 'lms_db';
            // delete the old one in update method and sync the new data
            // DB::connection($lmsConnection)->table('note_user')->where('note_id', $note->id)->delete();

            foreach ($note->recipients as $recipient) {
                DB::connection($lmsConnection)->table('note_user')->insert([
                    'note_id'     => $note->id,
                    'user_id'     => $recipient->id,
                    'acknowledge' => $recipient->pivot->acknowledge ?? 0,
                ]);
            }
        } catch (\Exception $e) {
            FailedSync::create([
                'model'         => get_class($note),
                'operation'     => 'sync_pivot',
                'data'          => json_encode(['note_id' => $note->id]),
                'error_message' => $e->getMessage(),
            ]);
            Log::error("CRM Sync Failed for note_user pivot - " . $e->getMessage());
        }
    }

    /**
     * sync the questions table (questions of the designed form )
     * and sync the recipients of thedesigned form(it linked with designed_forms table in answerd_forms table)
     * @param mixed $formId
     * @return void
     */
    public static function syncById($formId)
    {
        $form = DesignedForm::with('questions', 'recipients')->find($formId);

        foreach ($form->questions as $question) {
            $existingQuestion = DB::connection('lms_db')->table('questions')
                ->where('id', $question->id)
                ->first();

            if (! $existingQuestion) {
                DB::connection('lms_db')->table('questions')->insert([
                    'id'               => $question->id,
                    'type'             => $question->type,
                    'question'         => $question->question,
                    'is_required'      => $question->is_required,
                    'options'          => json_encode($question->options),
                    'designed_form_id' => $form->id,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

        $recipients = $form->recipients()->withPivot('answered')->get();

        Log::info('Recipients:', $recipients->toArray());

        foreach ($recipients as $recipient) {
            $exists = DB::connection('lms_db')->table('answerd_forms')
                ->where('designed_form_id', $form->id)
                ->where('user_id', $recipient->id)
                ->exists();

            if (! $exists) {
                DB::connection('lms_db')->table('answerd_forms')->insert([
                    'id'               => $recipient->pivot->id,
                    'designed_form_id' => $form->id,
                    'user_id'          => $recipient->id,
                    'answered'         => $recipient->pivot->answered ?? 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }

    /**
     * sync the class_trainee table 
     * this table contains users who assigned to this class (classId)
     * @param \Illuminate\Database\Eloquent\Model $user
     * @param array $classId
     * @return void
     */
    public static function syncTraineeClasses(Model $user,$classId)
    {
        Log::info('the class id: ' . $classId);
        Log::info('the user id: ' . $user);
        try {
            $lmsConnection = 'lms_db';

            // foreach ($classIds as $classId) {
            DB::connection($lmsConnection)->table('class_trainee')->insert([
                'class_id'   => $classId,
                'user_id'    => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // }

        } catch (\Exception $e) {
            FailedSync::create([
                'model'         => get_class($user),
                'operation'     => 'sync_class_trainee',
                'data'          => json_encode(['user_id' => $user->id, 'class_ids' => $classId]),
                'error_message' => $e->getMessage(),
            ]);

            Log::error("LMS Sync Failed for class_trainee - " . $e->getMessage());
        }
    }

    protected static  function normalizeAttributes(array $attributes): array
    {
        foreach ($attributes as $key => $value) {

            if ($value === "null" || $value === null) {
                $attributes[$key] = null;
                continue;
            }

            if (is_string($value) && in_array(substr(trim($value), 0, 1), ['[', '{'])) {
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $attributes[$key] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
                    continue;
                }
            }

            if (is_array($value)) {
                \Log::warning("⚠️ [LMS SYNC] Field {$key} is array before insert", [
                    'value' => $value
                ]);
                $attributes[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
                continue;
            }
        }

        return $attributes;
    }

    /**
     * Sync instructor's topics pivot table to LMS
     *
     * @param \Illuminate\Database\Eloquent\Model $instructor
     * @return void
     */
    protected static function syncInstructorTopics(Model $instructor)
    {
        try {
            $lmsConnection = 'lms_db';

            DB::connection($lmsConnection)->table('instructor_topic')
                ->where('instructor_id', $instructor->id)
                ->delete();

            if ($instructor->relationLoaded('topics') || method_exists($instructor, 'topics')) {
                $topics = $instructor->topics()->get();

                foreach ($topics as $topic) {
                    DB::connection($lmsConnection)->table('instructor_topic')->insert([
                        'instructor_id' => $instructor->id,
                        'topic_id'      => $topic->id,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            FailedSync::create([
                'model'         => get_class($instructor),
                'operation'     => 'sync_instructor_topics',
                'data'          => json_encode(['instructor_id' => $instructor->id]),
                'error_message' => $e->getMessage(),
            ]);
            Log::error("LMS Sync Failed for instructor_topic pivot - " . $e->getMessage());
        }
    }


    public static function syncRolePermissions(Role $role, $permissions)
    {
        try {
            $lmsConnection = 'lms_db';

            DB::connection($lmsConnection)->table('role_has_permissions')
                ->where('role_id', $role->id)
                ->delete();

            foreach ($permissions as $permissionId) {
                DB::connection($lmsConnection)->table('role_has_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("LMS Sync Failed for role permissions - " . $e->getMessage());
            FailedSync::create([
                'model' => Role::class,
                'operation' => 'sync_role_permissions',
                'data' => json_encode(['role_id' => $role->id, 'permissions' => $permissions]),
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    public static function syncUserRoles(User $user)
    {
        try {
            $lmsConnection = 'lms_db';

            DB::connection($lmsConnection)->table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->delete();

            $roles = $user->roles()->pluck('id');

            foreach ($roles as $roleId) {
                DB::connection($lmsConnection)->table('model_has_roles')->insert([
                    'role_id'    => $roleId,
                    'model_type' => User::class,
                    'model_id'   => $user->id,
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("LMS Sync Failed for model_has_roles - " . $e->getMessage());

            FailedSync::create([
                'model'         => User::class,
                'operation'     => 'sync_user_roles',
                'data'          => json_encode(['user_id' => $user->id, 'roles' => $user->roles->pluck('id')]),
                'error_message' => $e->getMessage(),
            ]);
        }
    }



}
