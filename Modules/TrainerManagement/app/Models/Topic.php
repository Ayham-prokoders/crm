<?php

namespace Modules\TrainerManagement\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TrainerManagement\Database\Factories\TopicFactory;

class Topic extends Model
{
    use HasFactory,SyncsWithLMS;
    protected $fillable = [
        'lang_code',
        'title',
        'description',
    ];
}
