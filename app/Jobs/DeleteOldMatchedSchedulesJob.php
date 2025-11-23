<?php

namespace App\Jobs;

use Modules\Lms\Models\Course;
use Modules\Lms\Models\Schedule;
use App\Services\CourseMatchService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteOldMatchedSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $targetCourseId;
    public int $oldMatchId;


    /**
     * Create a new job instance.
     */
    public function __construct(int $targetCourseId, int $oldMatchId)
    {
        $this->targetCourseId = $targetCourseId;
        $this->oldMatchId = $oldMatchId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $course = Course::find($this->targetCourseId);
        if (!$course) {
            \Log::warning("Course not found", ['targetCourseId' => $this->targetCourseId]);
            return;
        }

        $site = getSiteConfig($course->project_source);

        \Log::info("Deleting schedules", [
            'targetCourseId' => $this->targetCourseId,
            'oldMatchId' => $this->oldMatchId
        ]);

        $deletedCount = Schedule::withoutGlobalScope('project_source_l1')->where('course_id', $this->targetCourseId)
            ->where('match_id', $this->oldMatchId)
            ->delete();

        \Log::info("Schedules deleted count", ['count' => $deletedCount]);

        try {
            app(CourseMatchService::class)->deleteSchedulesFromTargetSite(
                $site,
                $this->oldMatchId
            );
            \Log::info("Remote schedules deletion dispatched", ['oldMatchId' => $this->oldMatchId]);
        } catch (\Throwable $e) {
            \Log::error("Failed to delete schedules from remote site", [
                'oldMatchId' => $this->oldMatchId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
