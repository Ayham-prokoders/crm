<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioSmsMessage;

class NoteSmsNotification extends Notification
{
    use Queueable;

    protected $note;
    protected $userId;

    public function __construct(Note $note, $userId)
    {
        $this->note = $note;
        $this->userId = $userId;
    }

    public function via($notifiable)
    {
        return ['twilio'];
    }



    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content("Hello, you have a new note: {$this->note->subject}");
    }

}
