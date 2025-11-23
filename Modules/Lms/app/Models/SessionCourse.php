<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
// use Modules\Lms\Traits\SessionCourseRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TrainerManagement\Models\TrainerAttendance;

class SessionCourse extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SyncsWithLMS;

    protected $table = 'session_courses';

    protected $fillable =[
        'lang_code',
        'title',
        'description',
        'status',
        'duration',
        'startDate',
        'classe_id'
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'session_id');
    }

    public function trainerAttendances()
    {
        return $this->hasMany(TrainerAttendance::class,'session_id');
    }



}
