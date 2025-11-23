<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use Modules\Lms\Models\Course;

trait FeedbackRelations {

    public function trainee()
    {
        return $this->belongsTo(User::class,'trainee_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }


}