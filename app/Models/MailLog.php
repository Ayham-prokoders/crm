<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailLog extends Model
{
    use HasFactory,SyncsWithLMS;
    

    protected $fillable = ['id','recipient', 'name', 'subject', 'body', 'attachments','status','user_id'];
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
}
