<?php

namespace Modules\TrainerManagement\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Modules\TrainerManagement\Traits\InstructorRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model implements Auditable
{
    use HasFactory,SyncsWithLMS;
    use \OwenIt\Auditing\Auditable;
    use InstructorRelations;
    

    protected $fillable = [
        'uuid',
        'slug',
        'user_id',
        'location',
        'rating',
        'field',
        'work_history',
        'category_id',
        'professional_summary',
        'experience',
        'qualification',
        'certification',
        'course_experience_lpc',
        'specilized_topics',
        'languages',
        'awards',
        'testimonials',
        'social_media_links',
        'portofolio_url',
        'linkedln_url',
        'training_modes',
        'availability',
        'country_availability',
        'date_of_submission',
        'speaking_engagements',
        'publications',
       'facebook',
       'instagram',
       'twitter',
       'whatsapp',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
