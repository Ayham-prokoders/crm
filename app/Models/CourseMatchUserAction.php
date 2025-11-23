<?php

namespace App\Models;

use App\Models\User;
use Modules\Lms\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseMatchUserAction extends Model
{
    protected $fillable = [
        'user_id',
        'source_course_id',
        'target_course_id',
        'action',
        'note',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sourceCourse() {
        return $this->belongsTo(Course::class, 'source_course_id');
    }

    public function targetCourse() {
        return $this->belongsTo(Course::class, 'target_course_id');
    }
}
