<?php

namespace App\Models;
use Illuminate\Support\Str;
use App\Traits\SyncsWithLMS;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SyncsWithLMS;
    protected $fillable=[
        'id',
        'user_id',
        // 'acknowledge',
        'subject',
        'message',
        'level',
        'type',
        'role_ids'
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
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'note_user', 'note_id', 'user_id')->withPivot('acknowledge');
    }
}
