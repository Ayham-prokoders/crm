<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncedForm extends Model
{
     protected $table = 'synced_forms';

    protected $fillable = [
        'remote_id','type','original_type','form_created_at',
        'data','site_key','synced_at'
    ];

    protected $casts = [
        'data' => 'array',
        'form_created_at' => 'datetime',
        'synced_at' => 'datetime'
    ];
}
