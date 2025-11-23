<?php
namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AlertNotification extends Notification implements ShouldQueue
{
    use Queueable;


    protected $user_id;
    protected $note;
    protected $url;
    public function __construct(Note $note,string $user_id)
    {
        $this->note = $note;
        $this->user_id = $user_id;
        $this->url ='/apps/alerts';
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
            'title' => __('message.alert'),
            'message' => __('message.you_have_alert'),
            'url' => $this->url,
            'user_id' => $this->user_id,
            'note'=>$this->note,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'date' => Carbon::now()->toDateTimeString(),
            'title' => __('message.note'),
            'message' => __('message.you_have_note'),
            'url' => $this->url,
            'user_id' => $this->user_id,
            'note'=>$this->note,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $notificationData = $this->toArray($notifiable);

        // $response = Http::post('https://prokoders.click/api/send-event', [
        //     'notification' => $notificationData,
        // ]);

        // if ($response->successful()) {
        //     \Log::info('Notification sent successfully to server.');
        // } else {
        //     $errorMessage = $response->json('message', '');
        //     $status = $response->status();
        //     \Log::error("Failed to send notification to server. Status: $status, Error: $errorMessage, Response: " . $response->body());
        //     throw new \RuntimeException("Failed to send notification to server. Status: $status, Error: $errorMessage");
        // }
        broadcast(new \App\Events\NotificationBroadcasted($notificationData));
        
        return new BroadcastMessage($notificationData);
    }
}
