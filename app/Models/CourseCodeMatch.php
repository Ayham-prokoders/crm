<?php

namespace App\Models;

use Modules\Lms\Models\Course;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CourseCodeMatch extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * fillable field
     * @var array
     */
    protected $fillable = [
        'source_course_id',
        'target_course_id',
        'applied_code',
        'status',
        'target_project',
        'note',
    ];

    /**
     * the source course
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Course, CourseCodeMatch>
     */
    public function sourceCourse()
    {
        return $this->belongsTo(Course::class, 'source_course_id');
    }

    /**
     * the target course that we want to match his code with source course's code
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Course, CourseCodeMatch>
     */
    public function targetCourse()
    {
        return $this->belongsTo(Course::class, 'target_course_id');
    }
}
