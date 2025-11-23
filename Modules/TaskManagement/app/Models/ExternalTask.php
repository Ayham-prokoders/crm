<?php

namespace Modules\TaskManagement\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TaskManagement\Database\Factories\ExternalTaskFactory;

class ExternalTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_code','status','cancellation_reason','description',
        'title','hotel_booking', 'hotel_name', 'hotel_fees',
        'hotel_paid', 'taxi_booking1', 'taxi_booking2', 'pre_questioner',
        'requirements', 'requirement_mentions','hotel_note','notes' ,'evaluation_by_email' ,'flight_ticket',
        'hotel_paid_2', 'sales_person', 'joining_email', 'location_sent', 'paid', 'payment_date',
        'zoom_link','tutor_report_received','trainer_agreement','tutor_rating','course_confirm',
        'confirm_tutor','priority','tutor_confirmation','tutor','is_new','contact',
        'trainees','company_name','city','price','course_name','course_date','course_duration','date'
    ];

    protected $casts = [
        'contact' => 'array',
        'requirement_mentions' => 'array',
        'tutor_rating' => 'float',
    ];

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

    public function getUrl(): string
    {
        return '/apps/external-tasks/' . $this->id;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
