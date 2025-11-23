<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FormsSyncService;
use Carbon\Carbon;

class SyncForms extends Command
{
    protected $signature = 'sync:forms 
                            {--incremental : Incremental sync since last sync}
                            {--full : Full sync of all data}
                            {--since= : Sync since specific timestamp}
                            {--site=* : Sites to sync (default: all)}';
    
    protected $description = 'Synchronize forms from remote APIs';
    
    protected $syncService;
    
    public function __construct(FormsSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }
    
    public function handle()
    {
        $sites = $this->option('site') ?: array_keys(config('services.sites', []));
        
        if (empty($sites)) {
            $this->error('No sites configured');
            return 1;
        }
        
        foreach ($sites as $siteKey) {
            $this->info("Syncing site: $siteKey");
            
            if ($this->option('incremental')) {
                $count = $this->syncService->incrementalSync($siteKey);
                $this->info("Incremental sync completed for $siteKey. Processed: $count forms");
            } 
            elseif ($this->option('since')) {
                $since = Carbon::parse($this->option('since'));
                $count = $this->syncService->syncSince($since, $siteKey);
                $this->info("Sync since {$since} completed for $siteKey. Processed: $count forms");
            }
            else {
                $count = $this->syncService->fullSync($siteKey);
                $this->info("Full sync completed for $siteKey. Processed: $count forms");
            }
        }
        
        $this->info('All sync operations completed');
        return 0;
    }
}