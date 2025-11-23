<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Lms\Models\{Course, Certificate};
use App\Http\Helper\CertificateHelper;

class SyncCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-certificates {project?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $certificateHelper;
    public function __construct(CertificateHelper $certificateHelper)
    {
        parent::__construct();
        $this->certificateHelper = $certificateHelper;
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
        $this->info('projectSource'.$projectSource);
        $lastId = 0;
        $certificateCount = 0;

        while ($lastId !== -1) {
            $response = $this->certificateHelper->getCertificates($lastId, $url,$projectSource);

            if (!isset($response['table_data'])) {
                logger()->error('Failed to fetch certificates from remote API.');
                $this->error('Failed to fetch certificates from remote API.');
                return 1;
            }
            $certificates = $response['table_data'];

            foreach ($certificates as $certificateData) {
                $certificate = Certificate::where('external_id', $certificateData['id'])->first();

                if ($certificate) {
                    // Update existing certificate
                    $certificate->update([
                        'course_custom_name' => $certificateData['course_custom_name'],
                        'course_type' => $certificateData['course_type'],
                        'course_id' => $certificateData['course_id'],
                        'first_name' => $certificateData['first_name'],
                        'middle_name' => $certificateData['middle_name'],
                        'last_name' => $certificateData['last_name'],
                        'ID_certificate' => $certificateData['ID_certificate'],
                        'image' => $certificateData['image'],
                        'pdf' => $certificateData['pdf'],
                        'user_id' => $certificateData['user_id'],
                        'origin' => 'WEBSITE',
                        'project_source' => $projectSource,
                        'created_at' => $certificateData['created_at'],
                        'updated_at' => $certificateData['updated_at'],
                    ]);
                } else {
                    // Create new certificate
                    Certificate::create([
                        'external_id' => $certificateData['id'],
                        'project_source' => $projectSource,
                        'course_custom_name' => $certificateData['course_custom_name'],
                        'course_type' => $certificateData['course_type'],
                        'course_id' => $certificateData['course_id'],
                        'first_name' => $certificateData['first_name'],
                        'middle_name' => $certificateData['middle_name'],
                        'last_name' => $certificateData['last_name'],
                        'ID_certificate' => $certificateData['ID_certificate'],
                        'image' => $certificateData['image'],
                        'pdf' => $certificateData['pdf'],
                        'user_id' => $certificateData['user_id'],
                        'origin' => 'WEBSITE',
                        'created_at' => $certificateData['created_at'],
                        'updated_at' => $certificateData['updated_at'],
                    ]);
                }

                $certificateCount++;
            }
            // Update $lastId for the next iteration
            $lastId = $response['next_step'];


        }

        // Delete certificates that were not found in the API response

        // logger()->info("Synced $certificateCount certificates.");

        $this->info('Certificates synchronized successfully.');

        return 0;
    }

}
