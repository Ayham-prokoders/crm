<?php

namespace Modules\Lms\Models;

use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\SessionCourse;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
// use Modules\Lms\Traits\AttendanceRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\AttendanceFactory;

class Attendance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    // use AttendanceRelations;

    protected $fillable =[
        'status',
        'note',
        'lang_code',
        'classe_id',
        'session_id',
        'trainee_id',
        'signature',
    ];

    public function session()
    {
        return $this->belongsTo(SessionCourse::class,'session_id');
    }

    public function trainee()
    {
        return $this->belongsTo(User::class,'trainee_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }


}
