<?php

namespace Modules\DealManagement\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DealManagement\Models\Invoice;
use Symfony\Component\Mime\Header\UnstructuredHeader;
use Illuminate\Support\Facades\Storage;
class SubmitInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $filePath;
    public $fileName;
    public $fileContent;

    public $sent_by;
    public $customBody;
    public $subject;

    public $mimeType;
    public function __construct($invoiceId , $sent_by, $filePath, $customBody = null, $subject = null)
    {
        $this->invoice = Invoice::find($invoiceId);
        $this->filePath = $filePath;
        $this->sent_by = $sent_by;
        $this->customBody = $customBody;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('dealmanagement::emails.invoice')
                    ->attach(Storage::path($this->filePath))
                    ->with([
                        'customBody' => $this->customBody,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        // Attach the mailable class name to the headers
                        $message->getHeaders()->add(new UnstructuredHeader('X-Mailable-Class', static::class));
                        $message->getHeaders()->addTextHeader('X-User-ID', $this->sent_by); 
                    });
    }



}
