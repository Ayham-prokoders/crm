<?php

namespace Modules\DealManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DealManagement\Database\Factories\InvoiceFactory;
// use Modules\DealManagement\Database\Factories\InvoiceFactory;
use Illuminate\Support\Str;
class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'deal_id',
        'bank_id',
        'invoice_type',
        'duration',
        'tax',
        'discount',
        'invoice_number',
        'custom_code',
        'is_percentage'

    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    protected static function newFactory()
    {
        return InvoiceFactory::new();
    }

    //accsessor
   
    public function getFinalPriceAttribute()
    {
        if (!$this->deal || $this->deal->price === null) {
            return 0;
        }
    
        $basePrice = $this->deal->price;
    
        if ($this->deal->type === 'company') {
            $trainees_count = $this->deal->users()->count();
            $basePrice *= $trainees_count;
        }
    
        $discount = $this->discount ?? 0;
        $isPercentage = $this->is_percentage ?? 0;
    
        if ($discount > 0) {
            if ($isPercentage == 1) {
                $discountAmount = $basePrice * ($discount / 100);
            } else {
                $discountAmount = $discount;
            }
            $basePrice = max(0, $basePrice - $discountAmount);
        }
    
        $taxAmount = $basePrice * (($this->tax ?? 0) / 100);
    
        return round($basePrice + $taxAmount, 2);
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $deal = $invoice->deal ?? Deal::find($invoice->deal_id);
            $prefix = 'XX';
    
            if ($deal) {
                if ($deal->type === 'company' && $deal->company) {
                    $prefix = strtoupper(Str::substr($deal->company->name, 0, 2));
                } elseif ($deal->user) {
                    $prefix = strtoupper(Str::substr($deal->user->name, 0, 2));
                }
            }
    
            $lastCode = self::where('custom_code', 'like', $prefix . '%')
                ->orderBy('custom_code', 'desc')
                ->value('custom_code');
    
            $next = 1;
            if ($lastCode && preg_match('/\d+$/', $lastCode, $matches)) {
                $next = (int)$matches[0] + 1;
            }
    
            $invoice->custom_code = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
            $invoice->invoice_number = strtoupper(Str::random(12));
        });
           
    }
}
