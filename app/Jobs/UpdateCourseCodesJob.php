<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Lms\Models\Course;

class UpdateCourseCodesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $oldPrefix;
    protected string $newPrefix;
    protected string $newCode;

    public function __construct(string $oldPrefix, string $newPrefix, string $newCode)
    {
        $this->oldPrefix = $oldPrefix;
        $this->newPrefix = $newPrefix;
        $this->newCode = $newCode;
    }

    public function handle(): void
    {
        $courses = Course::where('code', 'LIKE', $this->oldPrefix . '%')
        ->withoutGlobalScope('project_source_l1')
        ->get();

        foreach ($courses as $course) {
            $oldCourseCode = $course->code;

            if (strpos($oldCourseCode, $this->oldPrefix) === 0) {
                $suffix = substr($oldCourseCode, strlen($this->oldPrefix));
                $newCourseCode = $this->newPrefix . $suffix;

                try {
                    $course->update([
                        'code' => $newCourseCode,
                    ]);

                    Log::info("Course ID {$course->id} updated: new_code={$newCourseCode}");

                    // CMS sync
                    $siteKey = $course->project_source;
                    $site = config("services.sites.$siteKey");
                    $url = $site['url'] . '/api/sync-course-code';
                    $secret = $site['shared_secret'];
                    $timestamp = time();

                    $payload = [
                        'target_course_id' => $course->external_id,
                        'new_code' => $this->newCode,
                    ];

                    $signature = hash_hmac('sha256', $timestamp . json_encode($payload), $secret);

                    $response = Http::withHeaders([
                        'X-Timestamp' => $timestamp,
                        'X-Signature' => $signature,
                    ])->post($url, $payload);

                    if (!$response->successful()) {
                        throw new \Exception('Failed to sync with target site: ' . $response->body());
                    }

                } catch (\Exception $e) {
                    Log::error("Failed to update course ID {$course->id}: {$e->getMessage()}");
                }
            }
        }
    }
}
