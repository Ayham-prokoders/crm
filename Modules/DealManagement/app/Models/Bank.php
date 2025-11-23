<?php

namespace Modules\DealManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DealManagement\Database\Factories\BankFactory;
// use Modules\DealManagement\Database\Factories\BankFactory;

class Bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_holder',
        'bank_name',
        'sort_code',
        'swift_bic',
        'iban',
    ];

    protected static function newFactory()
    {
        return BankFactory::new();
    }
}
