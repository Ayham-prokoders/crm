<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedSync extends Model
{
    /**
     * fillable array
     * @var array
     */
    protected $fillable = [
        'model',
        'operation',
        'data',
        'error_message',
        'retry_count',
    ];
}
