<?php

namespace App\Listeners;

use App\Models\MailLog;
use App\Mail\InvitationEmail;
use Symfony\Component\Mime\Email;
use App\Mail\{ApologyInstructorMail ,SendDesignedFormMail ,WelcomeInstructorMail
                ,SurveyBuilderInvitationMail ,CourseAnnouncements ,UserCreatedMail};
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\Mime\Part\DataPart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DealManagement\Emails\SubmitInvoiceMail;

class LogSentMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        // Log::info('🚀 LogSentMail Listener Triggered');

        // if (!$event->message) {
        //     Log::error('❌ No message in MessageSent event');
        //     return;
        // }

        $message = $event->message;
        $mailableClass = 'Unknown';
        $userId = null;

        // Extract mailable class from headers
        if ($message instanceof Email) {
            $header = $message->getHeaders()->get('X-Mailable-Class');
            if ($header) {
                $mailableClass = $header->getBodyAsString();
            }
            $userIdHeader = $message->getHeaders()->get('X-User-ID');
            if ($userIdHeader) {
                $userId = (int) $userIdHeader->getBodyAsString();
            }
        }

        // Log::info('📧 Email Subject: ' . $message->getSubject());
        $recipients = $message instanceof Email && $message->getTo()
        ? implode(',', array_map(fn($to) => $to->getAddress(), $message->getTo()))
        : 'Unknown';

        $subject = $message->getSubject() ?? 'No Subject';

        $body = $event->data['customBody'] ?? null;

        if (!$body) {
            $htmlBody = method_exists($message, 'getHtmlBody') ? $message->getHtmlBody() : null;
            $textBody = method_exists($message, 'getTextBody') ? $message->getTextBody() : null;
            $body = $htmlBody ? strip_tags($htmlBody) : ($textBody ?? 'No Content');
            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
        }


        $name = 'Unknown';
        switch ($mailableClass) {
            case UserCreatedMail::class:
                $name = $event->data['user']->name ?? 'Unknown User';
                break;
            case WelcomeInstructorMail::class:
                $name = $event->data['instructor']->name ?? 'Unknown Instructor';
                break;
            case CourseAnnouncements::class:
                $name = $event->data['trainer']->name ?? 'Unknown Trainer';
                break;
            case ApologyInstructorMail::class:
                $name = $event->data['instructor']->name ?? 'Unknown Instructor';
                break;
            case SendDesignedFormMail::class:
                $name = $event->data['user']->name ?? 'Unknown User';
                break;
            case SurveyBuilderInvitationMail::class:
                $name = $event->data['user']->name ?? 'Unknown User';
                break;
            case SubmitInvoiceMail::class:
                $invoice = $event->data['invoice'] ?? null;
                $name = $invoice?->deal?->company?->name
                    ?? $invoice?->deal?->user?->name
                    ?? 'Unknown Name';
                break;
        }


        // Handle attachments
       foreach ($message->getAttachments() as $attachment) {
            if ($attachment instanceof DataPart) {
                $fileName = $attachment->getFilename();

                if ($mailableClass === SubmitInvoiceMail::class) {
                    $fileName = 'invoices/' . $fileName;
                }

                $attachments[] = $fileName;
            }
        }
        MailLog::create([
            'recipient' => $recipients,
            'name' => $name,
            'subject' => $subject,
            'body' => $body,
            'user_id' => $userId,
            'attachments' => json_encode($attachments ?? []),

        ]);
    }
}
