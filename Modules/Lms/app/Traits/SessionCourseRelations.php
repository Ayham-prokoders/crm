<?php

namespace Modules\Lms\Traits;

use Modules\Lms\Models\{Classe, Attendance};
use Modules\TrainerManagement\Models\TrainerAttendance;

trait SessionCourseRelations {
    
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
