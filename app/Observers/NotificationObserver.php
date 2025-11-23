<?php

namespace App\Observers;

use App\Services\LmsSyncService;
use Illuminate\Notifications\DatabaseNotification;

class NotificationObserver
{
    public function created(DatabaseNotification $notification)
    {
        LmsSyncService::sync($notification, 'create');
    }

    public function updated(DatabaseNotification $notification)
    {
        LmsSyncService::sync($notification, 'update');
    }

    public function deleted(DatabaseNotification $notification)
    {
        LmsSyncService::sync($notification, 'delete');
    }
}
