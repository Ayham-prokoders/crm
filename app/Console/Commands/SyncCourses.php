<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helper\CourseHelper;
use Modules\Lms\Models\{Course, Category, Schedule};
use Carbon\Carbon;

class SyncCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-courses {project?}';

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
            // 'LPC-EN' => [
            //     'url' => env('API_URL'),
            //     'source' => 'L1',
            // ],
            // 'LPC-AR' => [
            //     'url' => env('API_URL_AR'),
            //     'source' => 'L0',
            // ],
            // 'Regent-EN' => [
            //     'url' => env('REGENT_API_URL_EN'),
            //     'source' => 'R1',
            // ],
            // 'Regent-AR' => [
            //     'url' => env('REGENT_API_URL_AR'),
            //     'source' => 'R0',
            // ],
            // 'LMA-EN' => [
            //     'url' => env('LMA_API_URL_EN'),
            //     'source' => 'M1',
            // ],
            // 'LMA-AR' => [
            //     'url' => env('LMA_API_URL_AR'),
            //     'source' => 'M0',
            // ],
        ];
        $projectKey = $this->argument('project') ?? 'LPC-EN';

        if (!array_key_exists($projectKey, $projects)) {
            $this->warn("Invalid project key '{$projectKey}', using default 'LPC-EN'.");
            $projectKey = 'LPC-EN';
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

            // Process retrieved categories
            foreach ($categories as $categoryData) {
                // Check if the category already exists in the database
                $category = Category::where('id', $categoryData['id'])->first();

                if ($category) {
                    // Update existing category
                    $category->update([
                        'id' => $categoryData['id'],
                        'title' => $categoryData['title'],
                        'description' => $categoryData['description'],
                        'type' => $categoryData['type'],
                        'link_id' => $categoryData['link_id'],
                        'code' => $categoryData['code'],
                        'project_source' => $projectSource
                    ]);
                } else {
                    // Create new category
                    Category::create([
                        'id' => $categoryData['id'],
                        'title' => $categoryData['title'],
                        'description' => $categoryData['description'],
                        'type' => $categoryData['type'],
                        'code' => $categoryData['code'],
                        'project_source' => $projectSource
                    ]);
                }

                // Update $lastCategoryId for the next iteration
                $categoryCount++;

            }

            $lastCategoryId = $response1['next_step'];

        }

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
                $course = Course::where('id', $courseData['id'])->first();

                if ($course) {
                    // Update existing course
                    $course->update([
                        'id' => $courseData['id'],
                        'name' => $courseData['name'],
                        'description' => $courseData['description'],
                        'duration' => $courseData['duration'],
                        'days_content' => $courseData['days_content'],
                        'related_courses' => $courseData['related_courses'],
                        'category_id' => $courseData['category_id'],
                        'online' => $courseData['online'],
                        'project_source' => $projectSource
                    ]);
                } else {
                    // Create new course
                    $course = Course::create([
                        'id' => $courseData['id'],
                        'name' => $courseData['name'],
                        'description' => $courseData['description'],
                        'duration' => $courseData['duration'],
                        'days_content' => $courseData['days_content'],
                        'related_courses' => $courseData['related_courses'],
                        'category_id' => $courseData['category_id'],
                        'online' => $courseData['online'],
                        'project_source' => $projectSource
                    ]);
                }
                // Process schedules for the current course
                foreach ($courseData['schedules'] as $scheduleData) {
                    $startDate = $scheduleData['date'];
                    // $city = isset($scheduleData['city']) && isset($scheduleData['city']['name']) ? $scheduleData['city']['name'] : null;
                    $city = $scheduleData['city_id'];
                    $online = $scheduleData['online'];
                    $trainer_id = $scheduleData['instructor_id'];

                    //price
                    $price = $scheduleData['original_price'];
                    //new schedule
                    $existingSchedule = Schedule::where('external_id', $scheduleData['id'])->first();

                    if ($existingSchedule) {
                        $existingSchedule->update([
                            'start_date' => $startDate,
                            'city_id' => $city,
                            'online' => $online,
                            'trainer_id' => $trainer_id,
                            'price' => $price,
                            'course_id' => $course->id,
                        ]);
                    } else {
                        $course->schedules()->create([
                            'external_id' => $scheduleData['id'],
                            'start_date' => $startDate,
                            'city_id' => $city,
                            'online' => $online,
                            'trainer_id' => $trainer_id,
                            'price' => $price,
                        ]);
                    }
                }

                // Update $lastCourseId for the next iteration
                $courseCount++;
            }
            $lastCourseId = $response2['next_step'];

        }

        // Delete courses that were not found in the API response



        logger()->info("Synced $categoryCount categories and $courseCount courses.");

        $this->info('Courses and categories and schedules synchronized successfully.');

        return 0;
    }


}
