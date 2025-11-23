<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Modules\Lms\Traits\ClassRelations;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use ClassRelations;
    use SyncsWithLMS;

    protected $fillable =[
        'lang_code',
        'title',
        'description',
        'type',
        'startDate',
        'trainer_id',
        'content_days',
        'course_id',
        'course_type',
        'schedule_id',
        'price'
    ];


}
