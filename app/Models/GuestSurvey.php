<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class GuestSurvey extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['lang_code', 'designed_form_id', 'email', 'info'];

    public function designedForm()
    {
        return $this->belongsTo(DesignedForm::class);
    }

    // public function guestSurveyAnswers()
    // {
    //     return $this->hasMany(GuestSurveyAnswer::class);
    // }
}
