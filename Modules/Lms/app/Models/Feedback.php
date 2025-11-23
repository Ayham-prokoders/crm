<?php

namespace Modules\Lms\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Modules\Lms\Traits\FeedbackRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\FeedbackFactory;

class Feedback extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use FeedbackRelations;

    protected $fillable =[
        'message',
        'lang_code',
        'trainee_id',
        'type',
        'course_id',
        'trainer_id',
        'rate',
        'is_new',
        'trainers_rate',
        'materials_rate',
        'hospitality_rate',
        'hotel_rate',
    ];

}
