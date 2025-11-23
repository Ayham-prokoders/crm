<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SurveyBuilderRecipient extends Model
{
    use HasUuids;

    protected $fillable = [
        'survey_builder_id','user_id','hmac_token','answered_at'
    ];

    public function survey()
    {
        return $this->belongsTo(SurveyBuilder::class, 'survey_builder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
