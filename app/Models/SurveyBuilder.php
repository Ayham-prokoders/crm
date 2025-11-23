<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\SurveyCategory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SurveyBuilder extends Model implements Auditable
{
    use HasUuids, \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'fields', 'slug','survey_category_id'];

    protected static function booted()
    {
        static::creating(function ($survey) {
            if (empty($survey->slug)) {
                $survey->slug = Str::slug($survey->name . '-' . Str::random(6));
            }
        });
    }

    public function recipients()
    {
        return $this->hasMany(SurveyBuilderRecipient::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyBuilderAnswer::class, 'survey_id');
    }

    public function surveyCategory(){
        return $this->belongsTo(SurveyCategory::class);
    }

}
