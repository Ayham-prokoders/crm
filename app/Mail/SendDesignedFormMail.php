<?php

namespace App\Mail;

use App\Models\User;
use App\Models\DesignedForm;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class SendDesignedFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $sent_by;
    public $form;
    public $customBody;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct( User $user , $sent_by ,DesignedForm $form , $customBody = null, $subject = null)
    {
        $this->user = $user;
        $this->sent_by = $sent_by;
        $this->form = $form;
        $this->customBody = $customBody;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('email.send_designed_form')
            ->with([
                'user' => $this->user,
                'form' => $this->form,
                'customBody' => $this->customBody,
            ])
            ->withSymfonyMessage(function ($message) {
                // Attach the mailable class name to the headers
                $message->getHeaders()->add(new UnstructuredHeader('X-Mailable-Class', static::class));
                $message->getHeaders()->addTextHeader('X-User-ID', $this->sent_by); 
            });
    }
}
