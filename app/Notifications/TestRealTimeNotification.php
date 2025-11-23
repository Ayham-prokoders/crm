<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TestRealTimeNotification extends Notification
{
    use Queueable;

    protected string $message;

    public function __construct()
    {
        $this->message = 'test real time notification';
    }

    public function via($notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'id' => $this->id,
            'title' => 'new notification test',
            'message' => $this->message,
            'date' => now(),
            'user_id' => $notifiable->id,
            'sent_notificationt_by' => $notifiable->id,
            'icon' => 'tabler-send',
        ];
    }

    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toBroadcast($notifiable)
    {
        $notificationData = $this->toArray($notifiable);
        broadcast(new \App\Events\NotificationBroadcasted($notificationData));
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
