<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Language extends Model implements Auditable
{
    use HasFactory,SyncsWithLMS;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'lang_code',
        'name',
        'code',
    ];
}
