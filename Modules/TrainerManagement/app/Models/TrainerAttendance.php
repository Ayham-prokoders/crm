<?php

namespace Modules\TrainerManagement\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TrainerManagement\Traits\TrainerAttendanceRelations;
// use Modules\TrainerManagement\Database\Factories\TrainerAttendanceFactory;

class TrainerAttendance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use TrainerAttendanceRelations;

    protected $fillable =[
        'status',
        'note',
        'lang_code',
        'classe_id',
        'session_id',
        'trainer_id',
        'signature',
    ];
}
