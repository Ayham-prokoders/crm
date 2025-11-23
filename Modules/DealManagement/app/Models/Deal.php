<?php

namespace Modules\DealManagement\Models;

use App\Models\User;
use App\Traits\LogsActionHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DealManagement\Database\Factories\DealFactory;
use Modules\Lms\Models\{Classe,Course ,Company,ExternalCourse};
// use Modules\DealManagement\Database\Factories\DealFactory;

class Deal extends Model
{
    use HasFactory , LogsActionHistory;
    protected $moduleName = 'Deals';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'course_id',
        'course_type',
        'name',
        'classe_id',
        'company_id',
        'user_id',
        'price',
        'type',
        'currency',
        'language',
        'payment_method',
        'payment_mode'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function externalCourse()
    {
        return $this->belongsTo(ExternalCourse::class, 'course_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'deal_trainee', 'deal_id', 'trainee_id');
    }
    protected static function newFactory()
    {
        return DealFactory::new();
    }

}
