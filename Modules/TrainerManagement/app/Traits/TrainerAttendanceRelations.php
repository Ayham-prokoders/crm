<?php

namespace Modules\TrainerManagement\Traits;

use App\Models\User;
use Modules\Lms\Models\{Classe ,SessionCourse};

trait TrainerAttendanceRelations {
    public function session()
    {
        return $this->belongsTo(SessionCourse::class,'session_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }
}