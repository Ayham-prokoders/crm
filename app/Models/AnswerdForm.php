<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class AnswerdForm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    // use SyncsWithLMS;

    protected $fillable =[
        'id',
        'answered',
        'rate',
        'user_id',
        'message',
        'designed_form_id',
    ];

    protected $table = 'answerd_forms';


    public $incrementing = false;
    protected $keyType   = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
        // static::bootSyncsWithLMS();

    }
    public function designedForm()
    {
        return $this->belongsTo(DesignedForm::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
