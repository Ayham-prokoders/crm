<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SurveyCategory extends Model implements Auditable
{   
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name'];

}
