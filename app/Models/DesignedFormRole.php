<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignedFormRole extends Model
{
    use HasFactory;
    protected $fillable=[
        'designed_form_id',
        'role_id'
    ];
}
