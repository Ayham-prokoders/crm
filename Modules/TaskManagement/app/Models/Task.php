<?php

namespace Modules\TaskManagement\Models;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\LogsActionHistory;
use Illuminate\Database\Eloquent\Model;
// use Modules\RegisterManagement\Database\Factories\TaskFactory;
use Modules\DealManagement\Models\Deal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TaskManagement\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory, LogsActionHistory;
    protected $moduleName = 'Tasks';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_code','deal_id','assign_to','status','cancellation_reason','description',
        'title','duration','hotel_booking', 'hotel_name', 'hotel_fees',
        'hotel_paid', 'taxi_booking1', 'taxi_booking2', 'pre_questioner',
        'requirements', 'requirement_mentions','hotel_note','notes' ,'evaluation_by_email' ,'flight_ticket',
        'hotel_paid_2', 'sales_person', 'joining_email', 'location_sent', 'paid', 'payment_date',
        'zoom_link','tutor_report_received','trainer_agreement','tutor_rating','course_confirm',
        'confirm_tutor','priority','tutor_confirmation','tutor','is_new','contact_id'
    ];

    protected $casts = [
        'contact_id' => 'array',
        'requirement_mentions' => 'array',
        'tutor_rating' => 'float',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function contactDirectory()
    {
        return $this->hasMany(ContactDirectory::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->task_code = strtoupper(Str::random(12));
        });
         static::saving(function ($task) {
            if ($task->status == 'canceled' && empty($task->cancellation_reason)) {
                throw new \Exception('Cancellation reason is required when the task status is Cancelled.');
            }
        });
    }
    public function trainees()
    {
        return $this->belongsToMany(User::class, 'task_trainee', 'task_id', 'trainee_id');
    }

    public function getUrl(): string
    {
        return '/apps/tasks/' . $this->id;
    }

    protected static function newFactory()
    {
        return TaskFactory::new();
    }
}
