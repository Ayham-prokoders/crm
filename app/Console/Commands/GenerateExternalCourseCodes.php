<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Lms\Models\ExternalCourse;
use App\Services\GenerateExternalCourseCodeService;

class GenerateExternalCourseCodes extends Command
{
    protected $signature = 'external-courses:generate-codes';
    protected $description = 'Generate  code for all external courses';

    public function handle(GenerateExternalCourseCodeService $codeService)
    {
        $courses = ExternalCourse::whereNull('code')->get();

        if ($courses->isEmpty()) {
            $this->info('No external courses found without code.');
            return;
        }

        foreach ($courses as $course) {
            try {
                $code = $codeService->generate($course);
                $course->base_code = $code;
                $course->code = $code;
                $course->project_source = 'C1';
                $course->save();

                $this->info("Generated code {$code} for course ID {$course->id}");
            } catch (\Exception $e) {
                $this->error("Failed for course ID {$course->id}: " . $e->getMessage());
            }
        }

        $this->info('Code generation completed.');
    }
}

