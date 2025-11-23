<?php

namespace Modules\Lms\Models;

use App\Models\User;
use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\ExternalScheduleFactory;

class ExternalSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SyncsWithLMS;


    protected $fillable = ['start_date', 'city_id', 'online','course_id','price','external_id','project_source'];

    protected $with =['city'];

    public function externalCourse()
    {
        return $this->belongsTo(ExternalCourse::class,'course_id');
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
