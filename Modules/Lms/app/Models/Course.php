<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Modules\Lms\Traits\CourseRelations;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use CourseRelations;
    use SyncsWithLMS;


    protected $fillable = [
        'lang_code',
        'name',
        'id',
        'description',
        'duration',
        'schedule',
        'days_content',
        'related_courses',
        'category_id',
        'online',
        'code',
        'base_code',
        'project_source',
        'external_id',
        'deleted_from_source'
    ];

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('project_source_l1', function (Builder $builder) {
    //         $builder->where('project_source', 'L1');
    //     });
    // }
    //to unuse this scope in controller
    // Course::withoutGlobalScope('project_source_l1')->get();

}
