<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CourseAnnouncementsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $course_id;
    protected $classe_id;
    protected $user_id;
    protected $url;
    public function __construct(int $course_id, int $classe_id,string $user_id)
    {
        $this->course_id = $course_id;
        $this->classe_id = $classe_id;
        $this->user_id = $user_id;
        $this->url ='apps/annoncment/';
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->id,
            'date' => Carbon::now()->toDateTimeString(),
            'title' => __('message.course_announcement'),
            'message' => __('message.invite_to_course_announcement'),
            'url' => $this->url,
            'user_id' => $this->user_id,
            'course_id'=>$this->course_id,
            'classe_id'=>$this->classe_id
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'date' => Carbon::now()->toDateTimeString(),
            'title' => __('message.course_announcement'),
            'message' => __('message.invite_to_course_announcement'),
            'url' => $this->url,
            'user_id' => $this->user_id,
            'course_id'=>$this->course_id,
            'classe_id'=>$this->classe_id
        ];
    }

    public function toBroadcast($notifiable)
    {
        $notificationData = $this->toArray($notifiable);
        // // Log input data before sending
        //     \Log::info('Preparing to send broadcast notification', [
        //         'data' => $notificationData,
        //         'notifiable_id' => $notifiable->id ?? 'N/A',
        //         'channel' => 'home-' . $this->user_id,
        //     ]);

        //     try {
        //         $response = Http::post('https://prokoders.click/api/send-event', [
        //             'notification' => $notificationData,
        //         ]);
        
        //         // Log the actual response
        //         \Log::info('Server response', [
        //             'status' => $response->status(),
        //             'body' => $response->body()
        //         ]);
        
        //         if ($response->successful()) {
        //             \Log::info('Notification sent successfully to server.');
        //         } else {
        //             $errorMessage = $response->json('message', 'Unknown error');
        //             \Log::error("Failed to send notification to server.", [
        //                 'status' => $response->status(),
        //                 'error' => $errorMessage,
        //                 'response_body' => $response->body(),
        //             ]);
        //             throw new \RuntimeException("Failed to send notification to server. Status: " . $response->status() . ", Error: $errorMessage");
        //         }
        //     } catch (\Throwable $e) {
        //         // Catch unexpected exceptions and log them
        //         \Log::critical('Exception thrown while sending broadcast notification', [
        //             'exception' => $e->getMessage(),
        //             'trace' => $e->getTraceAsString(),
        //         ]);
        //         throw $e;
        //     }
        broadcast(new \App\Events\NotificationBroadcasted($notificationData));

        return new BroadcastMessage($notificationData);
    }

    // public function broadcastOn()
    // {
    //     return new Channel('home-' . $this->user_id);
    // }

    // public function broadcastAs()
    // {
    //     return 'CourseAnnouncementEvent';
    // }
}
