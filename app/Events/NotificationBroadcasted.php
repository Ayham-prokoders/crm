<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NotificationBroadcasted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(array $notification)
    {
        $this->notification = $notification;
        Log::info('Notification payload: ', $notification);
    }

    /**
     * Get the channels the event should broadcast on.
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        Log::info('broadcast test event');
        return new PrivateChannel('notifications.' . $this->notification['user_id']);
    }

    public function broadcastAs(): string
    {
        return 'NotificationBroadcasted';
    }

    public function broadcastWith(): array
    {
        Log::info(' Payload:', $this->notification);
        return $this->notification;
    }

}
