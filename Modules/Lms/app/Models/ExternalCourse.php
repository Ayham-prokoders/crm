<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Modules\DealManagement\Models\Deal;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\ExternalCourseFactory;

class ExternalCourse extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SyncsWithLMS;

    protected $fillable = [
        'lang_code',
        'name',
        'city_id',
        'price',
        'objective',
        'duration',
        'description',
        'category_id',
        'online',
        'days_content',
        'code',
        'base_code',
        'project_source',
        'external_id'
    ];

    protected $casts = [
        'days_content' => 'array',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function schedules()
    {
        return $this->hasMany(ExternalSchedule::class, 'course_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    //auto code
    protected static function booted()
    {
        static::creating(function ($course) {
            if (!$course->base_code) {
                $service = app(\App\Services\GenerateExternalCourseCodeService::class);
                $code = $service->generate($course);
                $course->base_code = $code;
                $course->code = $code;
                $course->project_source = 'C';
            }
        });
    }
}
