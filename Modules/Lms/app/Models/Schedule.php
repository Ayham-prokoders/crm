<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Modules\Lms\Traits\ScheduleRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use ScheduleRelations;
    use SyncsWithLMS;


    protected $fillable = ['start_date', 'city_id', 'online','trainer_id','course_id','price','external_id','project_source','match_id'];

    protected $with =['city'];
    protected static function booted(): void
    {
        static::addGlobalScope('project_source_l1', function (Builder $builder) {
            $builder->where('project_source', 'L1');
        });
    }
}
