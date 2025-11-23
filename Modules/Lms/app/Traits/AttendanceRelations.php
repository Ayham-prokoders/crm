<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use Modules\Lms\Models\{Classe, SessionCourse};

trait AttendanceRelations {
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