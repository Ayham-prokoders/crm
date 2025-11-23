<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $link;
    public $password;
    public function __construct($user,$password,$link)
    {
        $this->user = $user;
        $this->link = $link;
        $this->password=$password;
    }

    public function build()
    {
        // return $this->from('staging@lpcentre.net', 'London Premier Center')
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Welcome to LPC – Your login details')
            ->view('email.user_created')
             ->with([
                'user' => $this->user,
                'password' => $this->password,
                'link' => $this->link,
            ])
            ->withSymfonyMessage(function ($message) {
                // Attach the mailable class name to the headers
                $headers = $message->getHeaders();
                $headers->add(new UnstructuredHeader('X-Mailable-Class', static::class));
            });
    }


}
