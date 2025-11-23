<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Modules\Lms\Models\{Classe ,Course, ExternalCourse};
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SyncsWithLMS;
    protected $fillable=['user_id','available','classe_id','course_id','type','read','image','attachments'];

    protected $casts = [
        'attachments' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function externalCourse()
    {
        return $this->belongsTo(ExternalCourse::class, 'course_id');
    }
}
