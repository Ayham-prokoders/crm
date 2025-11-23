<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use App\Models\Invitation;
use App\Models\DesignedForm;
use Modules\TrainerManagement\Models\TrainerAttendance;
use Modules\Lms\Models\{Course, Content, Feedback ,Schedule ,Attendance , ExternalCourse, ExternalSchedule, SessionCourse};

trait ClassRelations {
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class,'schedule_id');
    }
    public function externalCourse()
    {
        return $this->belongsTo(ExternalCourse::class,'course_id');
    }

    public function externalSchedule()
    {
        return $this->belongsTo(ExternalSchedule::class,'schedule_id');
    }

    public function getActualCourseAttribute()
    {
        return $this->course_type === 'custom' ? $this->externalCourse : $this->course;
    }

    public function getActualScheduleAttribute()
    {
        return $this->course_type === 'custom' ? $this->externalSchedule : $this->schedule;
    }

    public function sessions()
    {
        return $this->hasMany(SessionCourse::class);
    }

    public function designedForms()
    {
        return $this->hasOne(DesignedForm::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class,'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function trainerAttendances()
    {
        return $this->hasMany(TrainerAttendance::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function trainees()
    {
        return $this->belongsToMany(User::class, 'class_trainee', 'class_id', 'user_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

}
