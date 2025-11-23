<?php

namespace Modules\RegisterManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\RegisterManagement\Database\Factories\EmailFactory;

class Mail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): EmailFactory
    // {
    //     // return EmailFactory::new();
    // }
}
