<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AssignedToClassNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $user_id;
    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

                'id' => $this->id,
                'date' => Carbon::now()->toDateTimeString(),
                'title' => __('message.class_assign'),
                'message' => __('message.assigned_to_new_class'),
                'url' => '/apps/classes',
                'user_id' => $this->user_id

        ];
    }

    public function toBroadcast($notifiable)
    {
        $notificationData = $this->toArray($notifiable);
        // // Send notification data to the server via HTTP POST request
        // $response = Http::post('https://prokoders.click/api/send-event', [
        //     'notification' => $notificationData,
        // ]);

        // if ($response->successful()) {
        //     // Log success if the request was successful
        //     \Log::info('Notification sent successfully to server.');
        // } else {
        //     // Log error if the request failed
        //     $errorMessage = $response->json('message');
        //     \Log::error('Failed to send notification to server. Error: ' . $errorMessage);
        //     throw new \RuntimeException('Failed to send notification to server. Error: ' . $errorMessage);
        // }
        broadcast(new \App\Events\NotificationBroadcasted($notificationData));

        return new BroadcastMessage($notificationData);
    }
    // public function toBroadcast($notifiable)
    // {
    //     $notificationData = $this->toArray($notifiable);

    //     // Log that the notification is being broadcasted
    //     \Log::info('Notification is being broadcasted: ' . json_encode($notificationData));

    //     // Return the notification data as a BroadcastMessage
    //     return new BroadcastMessage( ['notification' => $notificationData]);
    // }
    // public function broadcastOn()
    // {
    //     \Log::info('Notification is '.'home' . $this->user_id);
    //     return new Channel('home' . $this->user_id);
    // }

    // public function broadcastAs()
    // {
    //     return 'LpcEvent';
    // }

}
