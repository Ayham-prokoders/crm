<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\SyncsWithLMS;
use Modules\Lms\Models\Classe;
use App\Models\AnswerdFormPivot;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesignedForm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SyncsWithLMS;
    use HasFactory;
    protected $fillable = [
        'id',
        'slug',
        'title',
        'type',
        'description',
        'classe_id',
        'lang_code',
        'recipients',
        'image'
    ];


    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });

        // static::bootSyncsWithLMS(); 
    }


    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'answerd_forms', 'designed_form_id', 'user_id')
                    ->withPivot('answered')->using(AnswerdFormPivot::class)
                    ->withPivot(['id', 'answered']) // include any custom pivot fields
                    ->withTimestamps(); 
    }


public function roles()
{
    return $this->belongsToMany(Role::class, 'designed_form_roles', 'designed_form_id', 'role_id');
}
}
