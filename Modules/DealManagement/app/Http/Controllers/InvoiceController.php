<?php

namespace Modules\DealManagement\Http\Controllers;

use App\Models\MailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Modules\DealManagement\Models\Deal;
use Modules\DealManagement\Models\Invoice;
use Modules\DealManagement\Emails\SubmitInvoiceMail;
use Modules\DealManagement\Http\Requests\InvoiceRequest;
use Modules\DealManagement\Transformers\InvoiceResource;
use Modules\DealManagement\Transformers\InvoiceCollection;
use Modules\DealManagement\Http\Requests\SubmitInvoiceRequest;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = QueryBuilder::for(Invoice::class)
            // ->allowedFilters(['invoice_type', 'deal_id', 'bank_id'])
            ->allowedFilters([
                AllowedFilter::exact('invoice_type'),
                AllowedFilter::exact('invoice_number'),
                AllowedFilter::exact('deal_id'),
                AllowedFilter::exact('bank_id'),
                AllowedFilter::callback('deal_type', function ($query, $value) {
                $query->whereHas('deal', function ($q) use ($value) {
                    $q->where('type', $value);
                    });
                }),
            ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at')
            ->paginate($request->per_page);
        return ResponseHelper::success(new InvoiceCollection($invoices));
    }

    public function store(InvoiceRequest $request)
    {
        $invoice = Invoice::create($request->validated());

        return ResponseHelper::create(new InvoiceResource($invoice));
    }

    public function show(Invoice $invoice)
    {
        return ResponseHelper::success(new InvoiceResource($invoice));
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());
        return ResponseHelper::success(new InvoiceResource($invoice));
    }


    public function submitInvoice(SubmitInvoiceRequest $request, Invoice $invoice)
    {
        $validated = $request->validated();
        $deal = $invoice->deal;

        if (!$deal) {
            return ResponseHelper::DataNotFound('Deal not found');
        }

        $recipientEmail = $deal->type === 'company'
            ? ($deal->company->bill_email ?? $deal->company->email)
            : $deal->user->email;

        if (empty($recipientEmail)) {
            return ResponseHelper::operationFail('No recipient email found');
        }
        $name = $deal->type === 'company'
        ? ($deal->company->name)
        : ($deal->user->name);


        $invoiceFile = $validated['invoice_file'];
        $filePath = $invoiceFile->store('public/invoices');
        $sent_by= Auth::id();
        $template =  $validated['template'];
        $subject = $template['subject'];

        // Step 1: Decode JSON string
        $rawBody = $template['html_body'] ?? '';

        if (is_null($rawBody)) {
            return ResponseHelper::operationFail('Template body is not valid JSON');
        }

        // If it's wrapped in double quotes, it's JSON-encoded
        if (str_starts_with($rawBody, '"')) {
            $rawBody = json_decode($rawBody);
        }

        // Step 2: Remove leftover slashes (if still present)
        $rawBody = stripslashes($rawBody);

        // Step 3: Replace placeholders
        $replacedBody = str_replace(
            ['{{name}}', '{{invoice_number}}'],
            [$name,  $invoice->invoice_number],
            $rawBody
        );

        // Step 4: Decode any HTML entities
        $replacedBody = html_entity_decode($replacedBody);

            if ($template['status'] == 0) {
                // Save as draft
                MailLog::create([
                    'recipient' => $recipientEmail,
                    'name' => $name,
                    'subject' => $template['subject'] ?? 'No Subject',
                    'body' => $replacedBody,
                    'status' => 0,
                    'user_id' => $sent_by,
                    'attachments' => $filePath,
                ]);
            } else {
                 Mail::to($recipientEmail)->queue(
                    new SubmitInvoiceMail($invoice->id, $sent_by, $filePath, $replacedBody, $subject)
                );
            }
        return ResponseHelper::success('Invoice sent successfully');
    }


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return ResponseHelper::success();
    }
}
