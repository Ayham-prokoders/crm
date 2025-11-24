<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helper\CourseHelper;
use Modules\Lms\Models\{Course, Category, Schedule,City};
use Carbon\Carbon;

class SyncCourses2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-all-courses {project?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $courseHelper;
    public function __construct(CourseHelper $courseHelper)
    {
        parent::__construct();
        $this->courseHelper = $courseHelper;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $projects = [
            'LPC-EN' => [
                'url' => env('LPC_EN_URL'),
                'source' => 'L1',
            ],
            'LPC-AR' => [
                'url' => env('LPC_AR_URL'),
                'source' => 'L0',
            ],
            'Regent-EN' => [
                'url' => env('REGENT_URL_EN'),
                'source' => 'R1',
            ],
            'Regent-AR' => [
                'url' => env('REGENT_URL_AR'),
                'source' => 'R0',
            ],
            'LMA-EN' => [
                'url' => env('LMA_URL_EN'),
                'source' => 'M1',
            ],
            'LMA-AR' => [
                'url' => env('LMA_URL_AR'),
                'source' => 'M0',
            ],
        ];
        $projectKey = $this->argument('project');

        if (!array_key_exists($projectKey, $projects)) {
            $this->warn("Invalid project key '{$projectKey}'");
            return 0;
        }

        $projectConfig = $projects[$projectKey];
        $url = $projectConfig['url'];
        $projectSource = $projectConfig['source'];



        $lastCourseId = 0;
        $lastCategoryId = 0;
        $courseCount = 0;
        $categoryCount = 0;

        while ($lastCategoryId !== -1) {
            // Retrieve categories from the remote API


            $response1 = $this->courseHelper->getCategories($lastCategoryId, $url,$projectSource);

            if (!isset($response1['table_data'])) {
                logger()->error('Failed to fetch categories from remote API.');
                $this->error('Failed to fetch categories from remote API.');
                return 1;
            }
            $categories = $response1['table_data'];
            $processedCategoryIds = [];

            // Process retrieved categories
            foreach ($categories as $categoryData) {
                // Check if the category already exists in the database
                $category = Category::where('external_id', $categoryData['id'])
                    ->where('project_source', $projectSource)
                    ->withoutGlobalScope('project_source_l1')
                    ->first();
                $processedCategoryIds[] = $categoryData['id'];

                if ($category) {
                    // Update existing category
                    $category->update([
                        'external_id' => $categoryData['id'],
                        'title' => $categoryData['title'],
                        'description' => $categoryData['description'],
                        'type' => $categoryData['type'],
                        'link_id' => $categoryData['link_id'],
                        'code' => $categoryData['code'],
                        'project_source' => $projectSource
                    ]);
                    $this->info('update category' . $category->id);
                } else {
                    // Create new category
                    $category = Category::create([
                        'external_id' => $categoryData['id'],
                        'title' => $categoryData['title'],
                        'description' => $categoryData['description'],
                        'type' => $categoryData['type'],
                        'code' => $categoryData['code'],
                        'project_source' => $projectSource
                    ]);
                    $this->info('create category' . $category->id);
                }

                // Update $lastCategoryId for the next iteration
                $categoryCount++;

            }

            $lastCategoryId = $response1['next_step'];
            $extraCategories = Category::where('project_source', $projectSource)
                ->whereNotIn('external_id', $processedCategoryIds)
                ->withoutGlobalScope('project_source_l1')
                ->get();

            if ($extraCategories->isNotEmpty()) {
                $this->warn("Extra categories found in DB but not in API:");
                foreach ($extraCategories as $extraCategory) {
                    $this->warn("- ID: {$extraCategory->id}, External ID: {$extraCategory->external_id}, Title: {$extraCategory->title}");
                }
                // $extraCategories->each->delete();

            } else {
                $this->info("No extra categories found. All categories in DB match the API data.");
            }

        }

        $processedCourseIds = [];

        while ($lastCourseId !== -1) {
            // Retrieve courses from the remote API
            $response2 = $this->courseHelper->getCourses($lastCourseId, $url,$projectSource);
            if (!isset($response2['table_data'])) {
                logger()->error('Failed to fetch courses from remote API.');
                $this->error('Failed to fetch courses from remote API.');
                return 1;
            }
            $courses = $response2['table_data'];

            // Process retrieved courses
            foreach ($courses as $courseData) {
                $this->info('start courses');
                $processedCourseIds[] = $courseData['id'];
                $course = Course::where('external_id', $courseData['id'])
                    ->withoutGlobalScope('project_source_l1')
                    ->where('project_source', $projectSource)
                    ->first();
                $courseCategory = Category::where('external_id', $courseData['category_id'])
                    ->where('project_source', $projectSource)
                    ->withoutGlobalScope('project_source_l1')
                    ->first();

                if (!$courseCategory) {
                    $this->info('this course dose not have categopry' . $courseData['category_id']);
                    continue;
                }
                $this->info('find courseCategory' . $courseCategory->id);

                if ($course) {
                    // Update existing course
                    $course->update([
                        'external_id' => $courseData['id'],
                        'name' => $courseData['name'],
                        'description' => $courseData['description'],
                        'duration' => $courseData['duration'],
                        'days_content' => $courseData['days_content'],
                        'related_courses' => $courseData['related_courses'],
                        'category_id' => $courseCategory->id,
                        'online' => $courseData['online'],
                        'project_source' => $projectSource,
                        'deleted_from_source' => false,
                    ]);
                    $this->info('update course' . $course->id);
                } else {
                    // Create new course
                    $course = Course::create([
                        'external_id' => $courseData['id'],
                        'name' => $courseData['name'],
                        'description' => $courseData['description'],
                        'duration' => $courseData['duration'],
                        'days_content' => $courseData['days_content'],
                        'related_courses' => $courseData['related_courses'],
                        'category_id' => $courseCategory->id,
                        'online' => $courseData['online'],
                        'project_source' => $projectSource,
                        'deleted_from_source' => false,
                    ]);
                    $this->info('create category' . $category->id);
                }
                // Process schedules for the current course
                foreach ($courseData['schedules'] as $scheduleData) {
                    $startDate = $scheduleData['date'];
                    $online = $scheduleData['online'];
                    $trainer_id = $scheduleData['instructor_id'];
                    $price = $scheduleData['original_price'];

                    $cityId = null;
                    $externalCityId = $scheduleData['city_id'] ?? null;

                    if ($externalCityId) {
                        $city = City::where('external_id', $externalCityId)
                            ->where('project_source', $projectSource)
                            ->withoutGlobalScope('project_source_l1')
                            ->first();

                        if ($city) {
                            $cityId = $city->id;
                            $this->info("City found for id: $cityId");
                        } else {
                            $this->warn("City not found for external_id: $externalCityId — saving with city_id = null");
                        }
                    } else {
                        $this->info("No city_id provided — saving with city_id = null");
                    }
                    //new schedule
                    $existingSchedule = Schedule::where('external_id', $scheduleData['id'])
                        ->where('project_source', $projectSource)
                        ->withoutGlobalScope('project_source_l1')
                        ->first();

                    if ($existingSchedule) {
                        $existingSchedule->update([
                            'start_date' => $startDate,
                            'city_id' => $cityId,
                            'online' => $online,
                            'trainer_id' => $trainer_id,
                            'price' => $price,
                            'course_id' => $course->id,
                            'project_source' => $projectSource
                        ]);
                        $this->info('update schedule' . $existingSchedule->id);
                    } else {
                        $course->schedules()->create([
                            'external_id' => $scheduleData['id'],
                            'start_date' => $startDate,
                            'city_id' => $cityId,
                            'online' => $online,
                            'trainer_id' => $trainer_id,
                            'price' => $price,
                            'project_source' => $projectSource
                        ]);
                        $this->info('create schedule' . $course->id);
                    }
                }

                // Update $lastCourseId for the next iteration
                $courseCount++;
            }
            $lastCourseId = $response2['next_step'];

        }

        // Mark courses that were not found in the API response as deleted from the source
        $missingCourses = Course::where('project_source', $projectSource)
            ->whereNotIn('external_id', $processedCourseIds)
            ->whereNotNull('external_id')
            ->withoutGlobalScope('project_source_l1')
            ->get();

        foreach ($missingCourses as $missingCourse) {
            $missingCourse->update(['deleted_from_source' => true]);
            $this->warn("Course marked as deleted from source: ID {$missingCourse->id}, External ID {$missingCourse->external_id}");
        }


        logger()->info("Synced $categoryCount categories and $courseCount courses.");

        $this->info('Courses and categories and schedules synchronized successfully.');

        return 0;
    }


}
