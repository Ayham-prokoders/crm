<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestSurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['answer', 'question_id', 'guest_survey_id'];

    // protected $with=['question'];
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function guestSurveyAnswers()
    {
        return $this->hasMany(GuestSurveyAnswer::class, 'question_id');
    }
}
