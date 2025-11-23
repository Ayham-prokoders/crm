<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SurveyBuilderAnswer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable , HasUuids;

    protected $fillable = ['survey_id', 'user_id', 'answers'];

    public function survey()
    {
        return $this->belongsTo(SurveyBuilder::class, 'survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
