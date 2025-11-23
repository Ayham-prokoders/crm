<?php

namespace Modules\TaskManagement\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Events\NotificationBroadcasted;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AssignNotification extends Notification
{
    use Queueable;

    protected $user_id;
    protected $task;
    /**
     * Create a new notification instance.
     */
    public function __construct($user_id, $task)
    {
        $this->user_id = $user_id;
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'date' => Carbon::now()->toDateTimeString(),
            'title' => 'مهمة جديدة',
            'message' => 'تم تعيينك على مهمة: ' . $this->task->title,
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
        //     Log::info('Assign notification sent successfully.');
        // } else {
        //     Log::error('Failed to send assign notification: ' . $response->body());
        // }

    }
}

