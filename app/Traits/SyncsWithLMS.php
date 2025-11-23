<?php

namespace App\Traits;

use App\Models\FailedSync;
use App\Services\LmsSyncService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

trait SyncsWithLMS
{
    /**
     * shared boot method in the syncronized model
     * @return void
     */
    protected static function bootSyncsWithLMS()
    {
        static::created(function (Model $model) {
            LmsSyncService::sync($model,'create');
        });

        static::updated(function (Model $model) {
            LmsSyncService::sync($model,'update');
        });

        static::deleted(function (Model $model) {
            LmsSyncService::sync($model,'delete');
        });
    }

    
}
