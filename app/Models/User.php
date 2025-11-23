<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use App\Traits\SyncsWithLMS;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Modules\DealManagement\Models\Deal;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\{Announcement,DesignedForm};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Lms\Models\{Classe ,Attendance ,Certificate,Company};
use Modules\TrainerManagement\Models\{Instructor,TrainerAttendance};

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable ,HasRoles,SyncsWithLMS;
    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'middel_name',
        'last_name',
        'phone',
        'email',
        'job',
        'password',
        'company_id',
        'image',
        'current_role_id',
        'role',
        'first_login',
        'salutation',
        'nationality',
        'city',
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


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     /**
     * Get the classes where the user is a trainee.
     */
    // public function traineeClasses()
    // {
    //     return $this->belongsToMany(Classe::class, 'class_user', 'user_id', 'class_id');

    // }

    public function trainees()
    {
        return $this->belongsToMany(Classe::class, 'class_trainee', 'user_id', 'class_id');
    }

    /**
     * Get the class where the user is a trainer.
     */
    public function trainerClasses()
    {
        return $this->hasMany(Classe::class,'trainer_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class,'trainee_id');
    }

    public function trainerAttendance()
    {
        return $this->hasMany(TrainerAttendance::class,'trainer_id');
    }

    public function forms()
    {
        return $this->belongsToMany(DesignedForm::class ,'answerd_forms', 'designed_form_id', 'user_id')
        ->withPivot('answered');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function currentRole()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    // public function companySupervisors()
    // {
    //     return $this->hasMany(User::class, 'company_id')->whereHas('roles', function ($query) {
    //         $query->where('name', 'companySupervisor');
    //     });
    // }
}
