<?php

namespace App\Services;
use Modules\Lms\Models\Course;
use App\Models\CourseCodeMatch;
use Modules\Lms\Models\Schedule;
use Illuminate\Support\Facades\DB;
use App\Jobs\SyncCourseSchedulesJob;
use App\Models\CourseCodeMatchError;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\CourseMatchUserAction;


class CourseMatchService
{
    public function matchAndSyncCode(Course $sourceCourse, Course $targetCourse,string $siteKey)
    {
        try {
            return DB::transaction(function () use ($sourceCourse, $targetCourse, $siteKey) {
                $userId = Auth::id();
                $oldCode = $targetCourse->code;
                $newCode = $sourceCourse->code;
                $site = config("services.sites.$siteKey");
                if (!$site) {
                    throw new \Exception("Site configuration for '$siteKey' not found.");
                }
                $targetProject = $site['project'];

                $existingMatchFromSource = CourseCodeMatch::where('source_course_id', $sourceCourse->id)
                    ->where('target_project', $targetProject)
                    ->where('status', 'active')
                    ->first();

                if ($existingMatchFromSource) {
                    // Allow override only if matching again to the SAME target course
                    if ($existingMatchFromSource->target_course_id != $targetCourse->id) {
                        throw new \Exception(
                            "This source course is already matched with another course with name ["
                            . ($existingMatchFromSource->targetCourse->name ?? 'Unknown Course')
                            . "] in project [" 
                            . ($existingMatchFromSource->target_project ?? 'Unknown Project') 
                            . "]."
                        );                    }

                    // Soft-disable old record: it will be replaced by the new matching
                    $existingMatchFromSource->update(['status' => 'not_active']);

                    // Delete all old schedules in job
                    SyncCourseSchedulesJob::dispatchDeleteOldSchedules(
                        $targetCourse,
                        $existingMatchFromSource->id
                    );
                }

                $existingActiveMatch = CourseCodeMatch::where('target_course_id', $targetCourse->id)
                    ->where('status', 'active')
                    ->first();

                
                if ($existingActiveMatch) {
                    if ($existingActiveMatch->source_course_id !== $sourceCourse->id) {
                    SyncCourseSchedulesJob::dispatchDeleteOldSchedules(
                        $targetCourse,
                        $existingActiveMatch->id
                    );
    }
                    $existingActiveMatch->update(['status' => 'not_active']);
                } else {
                    CourseCodeMatch::create([
                        'source_course_id' => null,
                        'target_course_id' => $targetCourse->id,
                        'applied_code'     => $oldCode,
                        'status'           => 'not_active',
                        'target_project'   => $targetProject,
                        'note'             => 'Old code before matching',
                    ]);
                }

                $targetCourse->update(['code' => $newCode]);

                $match = CourseCodeMatch::create([
                    'source_course_id' => $sourceCourse->id,
                    'target_course_id' => $targetCourse->id,
                    'applied_code'     => $newCode,
                    'status'           => 'active',
                    'target_project'   => $targetProject,
                    'note'            => 'New matched code from source',
                ]);

                $this->syncCodeToTargetSite($site, $targetCourse->external_id, $newCode);
                $this->log(
                    'match',
                    $userId,
                    $sourceCourse->id,
                    $targetCourse->id,
                    "Matched course '{$sourceCourse->name}' to '{$targetCourse->name}'"
                );

                SyncCourseSchedulesJob::dispatch($sourceCourse, $targetCourse, $match->id)
                    ->delay(now()->addSeconds(3))
                    ->afterCommit();

                return true;
            });
        } catch (\Throwable $e) {
            CourseCodeMatchError::create([
                'source_course_id' => $sourceCourse->id,
                'target_course_id' => $targetCourse->id,
                'target_project'   => config("services.sites.$siteKey.project") ?? $siteKey,
                'error_message'    => $e->getMessage(),
                'context'          => [
                    'source_code' => $sourceCourse->code,
                    'target_code' => $targetCourse->code,
                    'site_key'    => $siteKey,
                ],
            ]);
            \Log::error("Matching failed", [
                'source_course_id' => $sourceCourse->id,
                'target_course_id' => $targetCourse->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function syncCodeToTargetSite($site, $targetCourseId, $newCode)
    {
        $url = $site['url'] . '/api/sync-course-code';
        $secret = $site['shared_secret'];
        $timestamp = time();

        $payload = [
            'target_course_id' => $targetCourseId,
            'new_code' => $newCode,
        ];

        $signature = hash_hmac('sha256', $timestamp . json_encode($payload), $secret);

        $response = Http::withHeaders([
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
        ])->post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception('Failed to sync with target site: ' . $response->body());
        }
    }


    public function getMatches(array $validated){
        $courses = Course::with([
            'category'                   => function ($query) {
                $query->withoutGlobalScope('project_source_l1');
            },
            'codeMatches.sourceCourse'   => fn($q)   => $q->with([
                'category' => fn($q) => $q->withoutGlobalScope('project_source_l1'),
            ]),

            'sourceMatches.targetCourse' => fn($q) => $q->with([
                'category' => fn($q) => $q->withoutGlobalScope('project_source_l1'),
            ]),
        ])
            ->filterMatches($validated)
            ->get();

        return $courses->map(function ($course) use ($validated) {
            $matches = $course->allMatches()->filter(function ($match) use ($validated, $course) {
                if (! empty($validated['status']) && $match->status !== $validated['status']) {
                    return false;
                }
                return $match->target_course_id === $course->id || $match->source_course_id === $course->id;
            })->map(function ($match) use ($course) {
                $isTarget = $match->target_course_id === $course->id;
                // $matchedCourse = $isTarget ? $match->sourceCourse : $match->targetCourse;

                return [
                    'applied_code'    => $match->applied_code,
                    'status'          => $match->status,
                    'matched_with'    => $isTarget
                    ? $match->sourceCourse?->name
                    : $match->targetCourse?->name,
                    'matched_with_id' => $isTarget
                    ? $match->source_course_id
                    : $match->target_course_id,
                    'project_source'  => $isTarget
                    ? $match->sourceCourse?->project_source
                    : $match->targetCourse?->project_source,
                    // 'category_id_of_matched_with'  => $matchedCourse?->category_id,
                    // 'category_name_of_matched_with'      => optional($matchedCourse?->category)->type,
                    'note'            => $match->note,
                    'created_at'      => $match->created_at,
                ];
            });

            return [
                'id'             => $course->id,
                'title'          => $course->name,
                'project_source' => $course->project_source,
                'category_id'    => $course->category_id,
                'category_name'  => optional($course->category)->type,
                'online'         => $course->online,
                'current_code'   => $course->current_matched_code,
                'matches'        => $matches->values(),
            ];
        });
    }


    public function log(string $action, string $userId, int $sourceCourseId, int $targetCourseId, string $note = null): void
    {
        CourseMatchUserAction::create([
            'user_id'          => $userId,
            'source_course_id' => $sourceCourseId,
            'target_course_id' => $targetCourseId,
            'action'           => $action,
            'note'             => $note,
        ]);
    }


    public function sendSchedulesToTargetProject($site, $targetCourseId, array $scheduleIds, int $matchId)
    {
        $url = $site['url'] . '/api/sync-course-schedules';
        $secret = $site['shared_secret'];
        $timestamp = time();

        $targetCourse = Course::withoutGlobalScope('project_source_l1')
            ->select('id', 'external_id')
            ->find($targetCourseId);

        if (!$targetCourse) {
            \Log::error('Target course not found for syncing schedules', [
                'target_course_id' => $targetCourseId,
                'match_id' => $matchId,
            ]);
            return;
        }

        $schedules = Schedule::withoutGlobalScope('project_source_l1')
            ->whereIn('id', $scheduleIds)
            ->with('city:id,slug')
            ->get();

            
            \Log::info('Schedules fetched for sending', [
                'target_course_id' => $targetCourseId,
                'schedule_ids' => $scheduleIds,
                'fetched_count' => $schedules->count(),
                'fetched_ids' => $schedules->pluck('id')->toArray(),
            ]);

        $schedulesArray = $schedules->map(fn($schedule) => [
            'start_date' => $schedule->start_date,
            'city_slug'  => optional($schedule->city)->slug, // for online -> null
            'online'     => $schedule->online,
            'trainer_id' => $schedule->trainer_id,
            // 'price'      => $schedule->price,
            'match_id'   => $matchId,
        ])->toArray();

        if (empty($schedulesArray)) {
            \Log::info("No new schedules to sync", ['target_course_id' => $targetCourseId]);
            return;
        }

        $payload = [
            'target_course_id' => $targetCourseId,
            'target_course_external_id' => $targetCourse->external_id, 
            'schedules' => $schedulesArray,
        ];

        $signature = hash_hmac('sha256', $timestamp . json_encode($payload), $secret);

        $response = \Http::withHeaders([
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
        ])->post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception('Failed to sync new schedules with target site: ' . $response->body());
        }

        \Log::info('New schedules synced successfully to target site', [
            'target_course_id' => $targetCourseId,
            'schedule_count' => count($schedules),
        ]);
    }


    public function deleteSchedulesFromTargetSite($site, $matchId)
    {
        $url = $site['url'] . '/api/delete-matched-schedules';
        $secret = $site['shared_secret'];
        $timestamp = time();

        $payload = ['match_id' => $matchId];
        $signature = hash_hmac('sha256', $timestamp . json_encode($payload), $secret);

        $response = \Http::withHeaders([
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
        ])->post($url, $payload);

        if (!$response->successful()) {
            \Log::error("Failed to delete schedules for match {$matchId}: " . $response->body());
            throw new \Exception('Failed to delete schedules in target site');
        }

        \Log::info("Successfully deleted schedules for match {$matchId} from target site.");
    }

}
