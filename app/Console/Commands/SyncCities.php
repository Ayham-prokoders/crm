<?php

namespace App\Console\Commands;

use App\Http\Helper\CityHelper;
use Illuminate\Console\Command;
use  Modules\Lms\Models\{City, Location};

class SyncCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-cities {project?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize cities and locations from external API';

    protected $cityHelper;

    public function __construct(CityHelper $cityHelper)
    {
        parent::__construct();
        $this->cityHelper = $cityHelper;
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

        $lastId = 0;
        $cityCount = 0;
        $lastLocationId = 0;

        // Sync Cities
        while ($lastId !== -1) {
            $response = $this->cityHelper->getCities($lastId,$url,$projectSource);

            if (!isset($response['table_data'])) {
                logger()->error('Failed to fetch cities from remote API.');
                $this->error('Failed to fetch cities from remote API.');
                return 1;
            }

            $cities = $response['table_data'];
            foreach ($cities as $cityData) {
                // Store or update the city in the database
                $city = City::updateOrCreate(
                    ['external_id' => $cityData['id'],
                            'project_source' =>$projectSource
                ],
                    [
                        'name' => $cityData['name'],
                        'description' => $cityData['description'],
                        'slug' => $cityData['link_id'],
                    ]
                );
                $cityCount++;
            }

            $lastId = $response['next_step'];
        }

        // Sync Locations related to Cities
        while ($lastLocationId !== -1) {
            $response = $this->cityHelper->getLocations($lastLocationId,$url,$projectSource);
            if (!isset($response['table_data'])) {
                logger()->error('Failed to fetch locations from remote API.');
                $this->error('Failed to fetch locations from remote API.');
                return 1;
            }

            $locations = $response['table_data'];
            foreach ($locations as $locationData) {
                // Find the related city using the city_id from the API response
                $city = City::where('external_id', $locationData['city_id'])
                ->where('project_source',$projectSource)
                ->first();

                // Ensure the city exists before adding the location
                if ($city) {
                    Location::updateOrCreate(
                        ['id' => $locationData['id']],
                        [
                            'title' => $locationData['title'],
                            'description' => $locationData['description'],
                            'lat' => $locationData['lat'],
                            'long' => $locationData['long'],
                            'city_id' => $city->id,
                        ]
                    );
                } else {
                    logger()->warning("City with external_id {$locationData['city_id']} not found. Skipping location.");
                }
            }

            $lastLocationId = $response['next_step'];
        }

        $this->info("Cities and locations synchronized successfully.");

        return 0;
    }
}
