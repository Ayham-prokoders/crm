<?php

namespace App\Jobs;

use Throwable;
use Modules\Lms\Models\City;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Schedule;
use Illuminate\Support\Facades\Log;
use App\Services\CourseMatchService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\DeleteOldMatchedSchedulesJob;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncCourseSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sourceCourse;
    protected $targetCourse;
    protected int $matchId;
    protected array $createdScheduleIds = [];

    public $tries = 3;
    public $timeout = 300; 

    /**
     * Create a new job instance.
     */
    public function __construct(Course $sourceCourse, Course $targetCourse, int $matchId)
    {
        $this->sourceCourse = $sourceCourse;
        $this->targetCourse = $targetCourse;
        $this->matchId = $matchId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting schedule sync for target course ID: {$this->targetCourse->id}");

        try {
            $sourceSchedules = $this->sourceCourse->schedules;

            if ($sourceSchedules->isEmpty()) {
                Log::warning("No schedules found for source course ID: {$this->sourceCourse->id}");
                return;
            }

            foreach ($sourceSchedules as $schedule) {
                try {
                    $this->syncSingleSchedule($schedule);
                } catch (Throwable $e) {
                    Log::error("Failed to sync schedule ID {$schedule->id}: {$e->getMessage()}", [
                        'schedule_id' => $schedule->id,
                        'course_source' => $this->sourceCourse->id,
                        'course_target' => $this->targetCourse->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // continue even if one of them failed
                    continue;
                }
            }
            if (!empty($this->createdScheduleIds)) {
                Log::info("Sending newly created schedules to external project", [
                    'target_course_id' => $this->targetCourse->id,
                    'new_schedule_ids' => $this->createdScheduleIds,
                    'match_id' => $this->matchId,
                ]);

                $site = getSiteConfig($this->targetCourse->project_source);
                Log::info("Created schedule IDs to send", ['ids' => $this->createdScheduleIds]);
                app(CourseMatchService::class)->sendSchedulesToTargetProject(
                    $site,
                    $this->targetCourse->id,
                    $this->createdScheduleIds,
                    $this->matchId
                );

            }

            Log::info("Schedule sync completed for target course ID: {$this->targetCourse->id}");
        } catch (Throwable $e) {
            Log::critical("Critical error in schedule sync for target course {$this->targetCourse->id}: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; 
        }
    }

    /**
     * sync single schedule
     * @param mixed $schedule
     * @throws \Exception
     * @return void
     */
    protected function syncSingleSchedule($sourceSchedule)
    {
        $cityId = null;

        if (!$sourceSchedule->online) {
            $sourceCity = $sourceSchedule->city;
            if (!$sourceCity) {
                throw new \Exception("Schedule {$sourceSchedule->id} is not online but has no city linked.");
            }

            $city = City::where('slug', $sourceCity->slug)->first();
            if (!$city) {
                throw new \Exception("City not found for slug '{$sourceCity->slug}' (source city id: {$sourceCity->id}).");
            }
            $cityId = $city->id;
        }

        $existingQuery = Schedule::where('course_id', $this->targetCourse->id)
            ->where('start_date', $sourceSchedule->start_date);

        if (!$sourceSchedule->online) {
            $existingQuery->where('city_id', $cityId);
        }

        $existing = $existingQuery->first();

        if ($existing) {
            $existing->update([
                'trainer_id' => $sourceSchedule->trainer_id,
                'match_id' => $this->matchId,
            ]);
            Log::info("Updated existing schedule ID {$existing->id}");
        } else {
            $newSchedule = $this->targetCourse->schedules()->create([
                'start_date' => $sourceSchedule->start_date,
                'city_id' => $cityId,
                'online' => $sourceSchedule->online,
                'trainer_id' => $sourceSchedule->trainer_id,
                'price' => $sourceSchedule->price,
                'external_id' => $sourceSchedule->external_id,
                'project_source' => $this->targetCourse->project_source,
                'match_id' => $this->matchId,
            ]);

            $this->createdScheduleIds[] = $newSchedule->id;
            Log::info("Created new schedule for course ID {$this->targetCourse->id}");
        }

        
    }

    public function failed(Throwable $exception)
    {
        Log::error("SyncCourseSchedulesJob failed permanently for course {$this->targetCourse->id}: {$exception->getMessage()}");
    }

    public static function dispatchDeleteOldSchedules(Course $targetCourse, int $oldMatchId)
    {
        return DeleteOldMatchedSchedulesJob::dispatch($targetCourse->id, $oldMatchId)
            ->delay(now()->addSecond())
            ->afterCommit();
    }
}
