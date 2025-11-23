<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Modules\DealManagement\Models\Invoice;

class GenerateInvoiceCustomCodes extends Command
{
    protected $signature = 'invoices:generate-custom-codes';
    protected $description = 'Generate custom_code for existing invoices';

    public function handle()
    {
        $prefixCounts = [];

        $invoices = Invoice::with(['deal.user', 'deal.company'])->get();

        foreach ($invoices as $invoice) {
            $deal = $invoice->deal;

            if (!$deal) continue;

            if ($deal->type === 'company' && $deal->company) {
                $prefix = strtoupper(Str::substr($deal->company->name, 0, 2));
            } elseif ($deal->user) {
                $prefix = strtoupper(Str::substr($deal->user->name, 0, 2));
            } else {
                $prefix = 'XX';
            }

            if (!isset($prefixCounts[$prefix])) {
                $prefixCounts[$prefix] = 1;
            } else {
                $prefixCounts[$prefix]++;
            }

            $code = $prefix . str_pad($prefixCounts[$prefix], 4, '0', STR_PAD_LEFT);
            $invoice->custom_code = $code;
            $invoice->save();

            $this->info("Generated code for invoice #{$invoice->id}: $code");
        }

        $this->info('All custom codes generated successfully.');
    }
}
