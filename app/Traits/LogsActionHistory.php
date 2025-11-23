<?php


namespace App\Traits;

use App\Models\ActionHistory;
use Illuminate\Support\Facades\Auth;

trait LogsActionHistory
{
    protected static function bootLogsActionHistory()
    {
        static::created(function ($model) {
            $model->logActionHistory('created', $model->toArray());
        });

        static::updated(function ($model) {
            $model->logActionHistory('updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logActionHistory('deleted', []);
        });
    }

    protected function logActionHistory(string $action, array $changes)
    {
        ActionHistory::create([
            'module' => $this->getModuleName(),
            'record_id' => $this->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'changes' => $changes,
        ]);
    }

    protected function getModuleName()
    {
        return property_exists($this, 'moduleName') ? $this->moduleName : class_basename($this);
    }
}
