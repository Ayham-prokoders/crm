<?php

namespace Modules\RegisterManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RegisterRequest extends Model implements Auditable
{
use \OwenIt\Auditing\Auditable;
use HasFactory;
    protected $fillable = [
        'external_id', 'course_name', 'course_date', 'course_city','cancellation_reason', 'salutation',
        'nationality', 'company', 'email', 'full_name', 'city', 'type', 'mobile',
        'payment_mode', 'payment_method', 'bill_to', 'participantName', 'participantEmail',
        'participantPhone', 'participantPosition','status','course_price','participants','course_id'
    ];

    protected $casts = [
        'participantName' => 'array',
        'participantEmail' => 'array',
        'participantPhone' => 'array',
        'participantPosition' => 'array',
        'participants' => 'array',
    ];
}
