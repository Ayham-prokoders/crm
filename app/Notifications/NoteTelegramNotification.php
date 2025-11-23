<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;

class NoteTelegramNotification extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user_id;
    protected $note;
    protected $url;
    public function __construct(Note $note,string $user_id)
    {
        $this->note = $note;
        $this->user_id = $user_id;
        $this->url ='';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }
//
    // public function toTelegram($notifiable)
    // {
    //     if (!empty($notifiable->telegram_chat_id)) {
    //         return TelegramMessage::create()
    //             ->content("Hello, you have a new note, subject: {$this->note->subject}
    //                 message : {$this->note->message}")
    //             ->to($notifiable->telegram_chat_id);
    //     }
    //     \Log::warning('No telegram_chat_id found for notifiable.');
    //     return null;
    // }
    public function toTelegram($notifiable)
{
    if (empty($notifiable->telegram_chat_id)) {
        \Log::warning('No telegram_chat_id found for notifiable.');
        return null;
    }

    // $acknowledgeUrl = env('PROJECT_BACKEND')."/api/ack-note?note_id={$this->note->id}&user_id={$this->user_id}";

    $acknowledgeUrl = env('PROJECT_FRONTEND').'/apps/alerts';
        return TelegramMessage::create()
        ->content("Hello, you have a new {$this->note->type}, subject: {$this->note->subject}
            message: {$this->note->message}")
        ->button('Acknowledge', $acknowledgeUrl)
        ->to($notifiable->telegram_chat_id);
}


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
