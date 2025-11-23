<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use Modules\Lms\Models\Course;

trait CertificateRelations {
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}