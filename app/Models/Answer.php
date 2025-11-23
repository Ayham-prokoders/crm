<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model implements Auditable
{
    use HasFactory,SyncsWithLMS;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'answer',
        'question_id',
        'user_id',
        'lang_code',
        'guest_survey_id'
    ];



    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });

        // static::bootSyncsWithLMS(); 
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    //for guest user
    public function guestSurvey()
    {
        return $this->belongsTo(GuestSurvey::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }
}
