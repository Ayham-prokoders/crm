<?php

namespace App\Jobs;

use Modules\Lms\Models\Course;
use Modules\Lms\Models\Schedule;
use Illuminate\Support\Facades\Log;
use App\Services\CourseMatchService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteMatchedSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetCourseId;
    protected $matchId;
    protected $site;

    public $tries = 3;
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct($targetCourseId, $matchId, $site)
    {
        $this->targetCourseId = $targetCourseId;
        $this->matchId = $matchId;
        $this->site = $site;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $targetCourse = Course::withoutGlobalScope('project_source_l1')->find($this->targetCourseId);
        if (!$targetCourse) {
            Log::warning("DeleteMatchedSchedulesJob skipped: target course not found", [
                'target_course_id' => $this->targetCourseId,
            ]);
            return;
        }

        $schedules = Schedule::withoutGlobalScope('project_source_l1')
            ->where('course_id', $this->targetCourseId)
            ->where('match_id', $this->matchId)
            ->get();

        if ($schedules->isEmpty()) {
            Log::info("DeleteMatchedSchedulesJob: no schedules found for match_id {$this->matchId}");
            return;
        }

        $deletedIds = $schedules->pluck('id')->toArray();

        Log::info("DeleteMatchedSchedulesJob: found schedules to delete locally", [
            'target_course_id' => $targetCourse->id,
            'match_id' => $this->matchId,
            'schedule_ids' => $deletedIds,
        ]);

        try {
            app(CourseMatchService::class)->deleteSchedulesFromTargetSite($this->site, $this->matchId);
            Log::info("DeleteMatchedSchedulesJob: deletion synced to target site successfully", [
                'site' => $this->site['base_url'] ?? 'unknown',
                'match_id' => $this->matchId,
            ]);
            Schedule::withoutGlobalScope('project_source_l1')
                ->whereIn('id', $deletedIds)
                ->delete();

            Log::info("DeleteMatchedSchedulesJob: local schedules deleted successfully", [
                'target_course_id' => $targetCourse->id,
                'deleted_ids' => $deletedIds,
            ]);
        } catch (\Throwable $e) {
            Log::error("DeleteMatchedSchedulesJob failed to sync deletion to target site", [
                'error' => $e->getMessage(),
                'match_id' => $this->matchId,
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $e)
    {
        Log::critical("DeleteMatchedSchedulesJob permanently failed", [
            'target_course_id' => $this->targetCourseId,
            'match_id' => $this->matchId,
            'error' => $e->getMessage(),
        ]);
    }
}