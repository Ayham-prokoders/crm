<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class SurveyBuilderInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $survey;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $survey, $link)
    {
        $this->user = $user;
        $this->survey = $survey;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('You have a new survey')
                    ->view('email.survey-builder-invitation')
                    ->with([
                        'user' => $this->user,
                        'survey' => $this->survey,
                        'link' => $this->link,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        $message->getHeaders()->add(new UnstructuredHeader('X-Mailable-Class', static::class));
                        $message->getHeaders()->addTextHeader('X-User-ID', $this->user->id);
                    });
    }
}
