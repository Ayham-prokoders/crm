<?php

namespace App\Console\Commands;

use App\Models\FailedSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetryFailedSyncs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:retry-failed-syncs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'retry all failed syncronization operations';

        /**
     * Max retry attempts before giving up.
     */
    protected int $maxRetries = 3;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $failedSyncs = FailedSync::where('retry_count', '<', $this->maxRetries)->get();

        foreach ($failedSyncs as $key => $sync) {
            $data = json_decode($sync->data, true);

            try {
                $tableName = config('lms_sync.models.' . $sync->model);

                if (! $tableName) {
                    throw new \Exception("Table mapping not found for model: {$sync->model}");
                }

                if ($sync->operation === 'create') {
                    DB::connection('lms_db')->table($tableName)->insert($data);
                } elseif ($sync->operation === 'update') {
                    DB::connection('lms_db')->table($tableName)
                        ->where('id', $data['id'])
                        ->update($data);
                } elseif ($sync->operation === 'delete') {
                    DB::connection('lms_db')->table($tableName)
                        ->where('id', $data['id'])
                        ->delete();
                }


                $sync->delete();
                Log::info("Successfully retried {$sync->operation} for {$sync->model}");
            } catch (\Exception $e) {
                $sync->increment('retry_count');
                Log::error("Retry failed for {$sync->operation} on {$sync->model}: " . $e->getMessage());
            }
        }

        $this->info("Retries completed.");

    }
}
