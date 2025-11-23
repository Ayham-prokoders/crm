<?php

namespace App\Console\Commands;

use App\Http\Helper\RegisterationRequestHelper;
use Illuminate\Console\Command;

class SyncRegisterRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-register-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync registration requests from mails table';

    protected $requestHelper;

    public function __construct(RegisterationRequestHelper $requestHelper)
    {
        parent::__construct();
        $this->requestHelper = $requestHelper;
    }


    public function handle()
    {
        $mailCount = $this->requestHelper->syncRegisterRequests();

        if ($mailCount > 0) {
            $this->info("Registration requests synchronized successfully. Total: $mailCount");
        } else {
            $this->warn("No new registration requests found.");
        }

        return 0;
    }

}
