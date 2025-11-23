<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCodeMatchError extends Model
{
      protected $fillable = [
        'source_course_id',
        'target_course_id',
        'target_project',
        'error_message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}
