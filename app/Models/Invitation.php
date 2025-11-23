<?php

namespace App\Models;

use Modules\Lms\Models\Classe;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model implements Auditable
{
use \OwenIt\Auditing\Auditable;
use HasFactory;

    protected $fillable=[
        'lang_code',
        'email',
        'token',
        'classe_id'
    ];
    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }
}
