<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class ApologyInstructorMail extends Mailable 
{
    use Queueable, SerializesModels;

    public $class;
    public $instructor;

    public function __construct($class, $instructor)
    {
        $this->class = $class;
        $this->instructor = $instructor;
    }

    public function build()
    {
        return $this->subject('Class Assignment Update')
            ->view('email.apology_instructor')
            ->withSymfonyMessage(function ($message) {
                // Attach the mailable class name to the headers
                $message->getHeaders()->add(new UnstructuredHeader('X-Mailable-Class', static::class));
            });
    }
}

