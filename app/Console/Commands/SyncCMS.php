<?php

namespace App\Console\Commands;

use App\Http\Helper\CMSHelper;
use App\Models\FooterSetting;
use Illuminate\Console\Command;

class SyncCMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-c-m-s';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync footer-setting from website';

    /**
     * Execute the console command.
     */
    protected $helper;
    public function __construct(CMSHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }
    public function handle()
    {
        $lastId = 0;
        $itemCount = 0;

        while ($lastId !== -1) {
            $response = $this->helper->getCMS($lastId);

            if (!isset($response['table_data'])) {
                logger()->error('Failed to fetch FooterSetting from remote API.');
                $this->error('Failed to fetch FooterSetting from remote API.');
                return 1;
            }
            $items = $response['table_data'];

            foreach ($items as $itemData) {
                $item = FooterSetting::where('external_id', $itemData['id'])->first();
                if ($item) {
                    // Update existing FooterSetting
                    $item->update([
                        'name' => $itemData['name'],
                        'value' => $itemData['value'],
                        'img' => $itemData['img'],
                        'alter_img' => $itemData['alter_img'],

                    ]);
                } else {
                    // Create new FooterSetting
                    FooterSetting::create([
                        'external_id' => $itemData['id'],
                        'name' => $itemData['name'],
                        'value' => $itemData['value'],
                        'img' => $itemData['img'],
                        'alter_img' => $itemData['alter_img'],
                    ]);
                }

                $itemCount++;
            }
            // Update $lastId for the next iteration
            $lastId = $response['next_step'];


        }

        // Delete FooterSetting that were not found in the API response

        // logger()->info("Synced $itemCount FooterSetting.");

        $this->info('FooterSetting synchronized successfully.');

        return 0;
    }
}
