<?php

namespace App\Console\Commands;

use App\Http\Helper\CourseHelper;
use Illuminate\Console\Command;
use Modules\Lms\Models\Category;

class UpdateCategoryCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:category {project?}';

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
                'url' => env('API_URL'),
                'source' => 'L1',
            ],
            'LPC-AR' => [
                'url' => env('API_URL_AR'),
                'source' => 'L0',
            ],
            'Regent-EN' => [
                'url' => env('REGENT_API_URL_EN'),
                'source' => 'R1',
            ],
            'Regent-AR' => [
                'url' => env('REGENT_API_URL_AR'),
                'source' => 'R0',
            ],
            'LMA-EN' => [
                'url' => env('LMA_API_URL_EN'),
                'source' => 'M1',
            ],
            'LMA-AR' => [
                'url' => env('LMA_API_URL_AR'),
                'source' => 'M0',
            ],
        ];
        $projectKey = $this->argument('project') ?? 'LPC-EN';

        if (!array_key_exists($projectKey, $projects)) {
            $this->warn("Invalid project key '{$projectKey}', using default 'LPC-EN'.");
            $projectKey = 'LPC-EN';
        }

        $projectConfig = $projects[$projectKey];
        $url = $projectConfig['url'];
        $projectSource = $projectConfig['source'];



        $lastCategoryId = 0;
        $categoryCount = 0;

        while ($lastCategoryId !== -1) {
            // Retrieve categories from the remote API


            $response1 = $this->courseHelper->getCategories($lastCategoryId, $url);

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
                    // $category->update([
                    //     'id' => $categoryData['id'],
                    //     'title' => $categoryData['title'],
                    //     'description' => $categoryData['description'],
                    //     'type' => $categoryData['type'],
                    //     'link_id' => $categoryData['link_id'],
                    //     'code' => $categoryData['code'],
                    //     'project_source' => $projectSource
                    // ]);
                    $category->title = $categoryData['title'];
                    $category->description = $categoryData['description'];
                    $category->type = $categoryData['type'];
                    $category->link_id = $categoryData['link_id'];
                    $category->code = $categoryData['code'];
                    $category->project_source = $projectSource;

                    $category->save();

                    $this->info("Updated category ID {$category->id} - Title: '{$categoryData['type']}'");
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
                    $this->info("Created new category - Title: '{$categoryData['type']}'");
                }

                // Update $lastCategoryId for the next iteration
                $categoryCount++;

            }

            $lastCategoryId = $response1['next_step'];

        }

        logger()->info("Synced $categoryCount categories");

        $this->info('categories synchronized successfully.');

        return 0;
    }
}
