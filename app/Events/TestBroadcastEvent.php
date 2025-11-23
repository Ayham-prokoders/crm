<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestBroadcastEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;

    public function __construct(public array $data)
    {
        $this->userId = $data['user_id'];
    }

    public function broadcastOn(): Channel
    {
        Log::info("✅ Broadcasting on: notifications.{$this->userId}");
        return new PrivateChannel("notifications.{$this->userId}");
    }

    public function broadcastAs(): string
    {
        return 'NotificationBroadcasted';
    }

    public function broadcastWith(): array
    {
        Log::info("📦 Broadcasting payload:", $this->data);
        return $this->data;
    }
}
