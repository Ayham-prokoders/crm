<?php

namespace Modules\TaskManagement\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Events\NotificationBroadcasted;
use Modules\TaskManagement\Models\Task;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\TaskManagement\Models\ExternalTask;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CommentMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user_id;
    protected $task;

    public function __construct($user_id, $task)
    {
        $this->user_id = $user_id;
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
            'date' => Carbon::now()->toDateTimeString(),
            'message' => 'تم الإشارة اليك بكومنت على المهمة: ' . $this->task->title,
            'url' => $this->task->getUrl(),
            'user_id' => $this->user_id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $notificationData = $this->toArray($notifiable);
        broadcast(new NotificationBroadcasted($notificationData));

        return new BroadcastMessage($notificationData);

        // $response = Http::post('https://prokoders.click/api/send-event', [
        //     'notification' => $notificationData,
        // ]);

        // if ($response->successful()) {
        //     Log::info('Notification sent successfully to server.');
        // } else {
        //     $errorMessage = $response->json('message', '');
        //     $status = $response->status();
        //     Log::error("Failed to send notification to server. Status: $status, Error: $errorMessage, Response: " . $response->body());
        //     throw new \RuntimeException("Failed to send notification to server. Status: $status, Error: $errorMessage");
        // }

    }
}
