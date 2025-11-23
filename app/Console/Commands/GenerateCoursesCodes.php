<?php

namespace App\Console\Commands;

use App\Services\CourseSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Lms\Models\Course;

class GenerateCoursesCodes extends Command
{
    protected $signature = 'courses:generate-codes';
    protected $description = 'Generate sequential codes for all courses based on their category code';

    public function handle()
    {
        $this->info('Generating course codes...');

        // Retrieve all courses with their associated category and group them by category_id
        // $coursesGroupedByCategory = Course::with('category')
        //     ->withoutGlobalScope('project_source_l1')
        //     ->get()
        //     ->groupBy('category_id');
        $courses = Course::withoutGlobalScope('project_source_l1')
            ->get();

        $courses->load([
            'category' => function ($query) {
                $query->withoutGlobalScope('project_source_l1');
            }
        ]);

        $coursesGroupedByCategory = $courses->groupBy('category_id');

        foreach ($coursesGroupedByCategory as $categoryId => $courses) {
            $updatedCourses = [];
            $category = $courses->first()->category;

            // Skip if the category or its code is missing
            if (!$category || !$category->code) {
                $this->warn("Skipping category ID {$categoryId} (missing category or category code).");
                continue;
            }

            // Extract the first three letters of the category code in uppercase
            $categoryCode = strtoupper(substr($category->code, 0, 3));

            // Determine the highest existing serial number within this category
            $maxIndex = 0;
            foreach ($courses as $course) {
                if ($course->code) {
                    // Extract the last 3 characters of the course code (expected to be the serial number)
                    $serialPart = substr($course->base_code, -3);
                    if (is_numeric($serialPart)) {
                        $num = intval($serialPart);
                        if ($num > $maxIndex) {
                            $maxIndex = $num;
                        }
                    }
                }
            }

            // Start numbering from the next serial number after the highest existing one
            $index = $maxIndex + 1;

            foreach ($courses as $course) {
                try {
                    // Skip courses that already have a code
                    if ($course->code) {
                        $this->info("Skipping course ID {$course->id} (already has code).");
                        continue;
                    }

                    $projectKey = $course->project_source;

                    // Skip courses without a project source key
                    if (!$projectKey) {
                        $this->warn("Skipping course ID {$course->id} (missing project source).");
                        continue;
                    }

                    // Create the serial part with leading zeros to make it 3 digits
                    $serial = str_pad($index, 3, '0', STR_PAD_LEFT);

                    // Build the new course code in the format: CATEGORYCODE + PROJECTKEY + SERIAL
                    $newCode = "{$categoryCode}{$projectKey}{$serial}";

                    // Update the course code in the database
                    $course->update(['code' => $newCode]);
                    $course->update(['base_code' => $newCode]);

                    $updatedCourses[] = [
                        'target_course_id' => $course->external_id,
                        'new_code' => $newCode,
                    ];

                    $this->info("Updated course ID {$course->id} with new code: {$newCode}");


                    // Increment index for the next course in this category
                    $index++;

                } catch (\Exception $e) {
                    $this->error("Failed to update course ID {$course->id}: {$e->getMessage()}");
                }
            }
            if (!empty($updatedCourses)) {
                $this->syncCodesToOtherProject($updatedCourses, $category->project_source);
            }
        }

        $this->info('All courses updated successfully!');
    }


    protected function syncCodesToOtherProject(array $updatedCourses, $siteKey)
    {
        $site = config("services.sites.$siteKey");

        if (!$site) {
            $this->error("Site configuration not found in services.php for key $siteKey");
            return;
        }
        $secretKey = $site['shared_secret'];
        $url = $site['url'] . '/api/sync-multiple-course-code';


        $timestamp = time();
        $bodyJson = json_encode($updatedCourses);

        $dataToSign = $timestamp . $bodyJson;
        $hmacSignature = hash_hmac('sha256', $dataToSign, $secretKey);

        try {
            \Log::info('HMAC client data', [
                'timestamp' => $timestamp,
                'bodyJson' => $bodyJson,
                'data_to_sign' => $dataToSign,
                'client_hmac' => $hmacSignature,
            ]);

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'X-Timestamp' => $timestamp,
                'X-Signature' => $hmacSignature,
            ])->post($url, $updatedCourses);


            if ($response->successful()) {
                $this->info("Codes synced successfully to  project." . $siteKey);
            } else {
                $this->error("Failed to sync codes: HTTP " . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            $this->error("Exception during sync: " . $e->getMessage());
        }
    }
}
