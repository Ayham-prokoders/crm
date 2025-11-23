<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model implements Auditable
{
use \OwenIt\Auditing\Auditable;
use HasFactory;
use SyncsWithLMS;

    protected $fillable = [
        'id',
        'type',
        'question',
        'options',
        'designed_form_id',
        'lang_code',
        'is_required'
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

        static::bootSyncsWithLMS(); 
    }
    
    protected $with=['answers'];
    public function designedForm()
    {
        return $this->belongsTo(DesignedForm::class,'designed_form_id');
    }
    // public function guestSurveyAnswers()
    // {
    //     return $this->hasMany(GuestSurveyAnswer::class);
    // }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

}
