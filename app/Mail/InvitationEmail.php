<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;
class InvitationEmail extends Mailable 
{
    use Queueable, SerializesModels;

    public $invitation;
    public $registrationLink;
    /**
     * Create a new message instance.
     */

    public function __construct(Invitation $invitation,$registrationLink)
    {
        $this->invitation = $invitation;
        $this->registrationLink= $registrationLink;
    }
    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Invitation Email')
                    ->view('email.invitation');
    }
}
