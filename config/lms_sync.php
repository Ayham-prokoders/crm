<?php

use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use App\Models\Answer;
use App\Models\MailLog;
use App\Models\Language;
use App\Models\Question;
use App\Models\Permission;
use App\Models\AnswerdForm;
use App\Models\Announcement;
use App\Models\DesignedForm;
use App\Models\EmailBuilder;
use Modules\Lms\Models\City;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Company;
use Modules\Lms\Models\Content;
use Modules\Lms\Models\Category;
use Modules\Lms\Models\Schedule;
use Modules\Lms\Models\Certificate;
use Modules\Lms\Models\SessionCourse;
use Modules\Lms\Models\ExternalCourse;
use Modules\Lms\Models\ExternalSchedule;
use Modules\Lms\Models\ExternalCertificate;
use Modules\TrainerManagement\Models\Topic;
use Modules\TrainerManagement\Models\Instructor;
use Illuminate\Notifications\DatabaseNotification;

/**
 * in this file we will add the models that we want to sync with LMS system
 */
return [
    'models' => [
        Classe::class => 'classes',
        Course::class => 'courses',
        Content::class => 'contents',
        Company::class => 'companies',
        City::class => 'cities',
        Category::class => 'categories',
        Certificate::class => 'certificates',
        User::class => 'users',
        Schedule::class => 'schedules',
        Note::class => 'notes',
        Announcement::class => 'announcements',
        Question::class => 'questions',
        DesignedForm::class => 'designed_forms',
        AnswerdForm::class => 'answerd_forms',
        Instructor::class => 'instructors',
        Language::class => 'languages',
        Topic::class => 'topics',
        SessionCourse::class => 'session_courses',
        Answer::class => 'answers',
        DatabaseNotification::class => 'notifications',
        ExternalCourse::class => 'external_courses',
        ExternalSchedule::class => 'external_schedules',
        ExternalCertificate::class => 'external_certificates',
        MailLog::class => 'mail_logs',
        EmailBuilder::class => 'email_builders',
        Permission::class => 'permissions',
        Role::class => 'roles',

    ]
];