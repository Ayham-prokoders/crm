<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use Modules\Lms\Models\{Course ,City};

trait ScheduleRelations {
     /**
     * Get the course that owns the schedule.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }
}