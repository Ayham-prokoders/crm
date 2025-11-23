<?php

use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Api\{UserController,RoleController,PermissionController,DashboardController,
    AuthController ,ProfileController,
    InvitationController,
    ContentController,BlogController,AuditController,
    QuestionController,DesignedFormController,AnswerController,FileController,NotificationController,
    AnnouncementController,NoteController,TelegramWebhookController,ActionHistoryController,
    GuestSurveyController,LanguageController,TokenController,ReportController,IntegrationController

};
use App\Models\FailedSync;
use Illuminate\Http\Request;
use App\Models\CourseCodeMatch;
use App\Events\TestBroadcastEvent;
use App\Http\Helper\ResponseHelper;
use App\Models\CourseCodeMatchError;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Api\ContactController;
use App\Notifications\TestRealTimeNotification;
use App\Http\Controllers\SurveyCategoryController;
use App\Http\Controllers\Api\CourseMatchingController;
use App\Http\Controllers\CourseMatchUserActionController;
use App\Http\Controllers\{EmailBuilderController,MailLogController,SurveyBuilderController,SurveyBuilderAnswerController};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/printRoles', [AuthController::class, 'printRoles']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/mail-logs', [MailLogController::class, 'getMailLog']);
Route::post('/notifications/receive', [NotificationController::class, 'receiveLmsNotification'])
    ->middleware('verify.lms');

/*
|--------------------------------------------------------------------------
| Email Verification OTP Routes
|--------------------------------------------------------------------------
*/
Route::post('resend-verification-otp', [OtpController::class, 'resendOtp']);
Route::post('verify-otp', [OtpController::class, 'verifyOtp']);
/*
|--------------------------------------------------------------------------
| SMS Verification OTP Routes
|--------------------------------------------------------------------------
*/
// Route::post('send-sms-otp', [OtpController::class, 'sendOtpBySms']);



//LOG
Route::delete('/reset-log', [LogController::class, 'resetLog']);
Route::get('/logs', [LogController::class, 'getLog']);
Route::get('/survey/{slug}', [SurveyBuilderController::class, 'showBySlug']);
Route::post('/survey-answers/{slug}', [SurveyBuilderAnswerController::class, 'submitAnswers'])->middleware('hmac');
Route::post('/survey-upload-file', [FileController::class, 'uploadFile'])->middleware('xss');
Route::get('/footer-data', [SurveyBuilderController::class, 'getFooterData'])->middleware('hmac');
Route::get('/sync/email-builders', [EmailBuilderController::class, 'syncData']);

Route::get('/last-failed-sync', function () {
    $last = FailedSync::latest()->first();
    return response()->json($last);
});
Route::get('/last-failed-matching', function () {
    $match = CourseCodeMatchError::latest()->first();
    return response()->json($match);
});
Route::get('/matchs', function () {
    $match = CourseCodeMatch::latest()->first();
    return response()->json($match);
});

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    //API routes for UserController
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/users', [UserController::class, 'index']);


    //API routes for RoleController
    Route::post('/roles', [RoleController::class, 'store']);
    Route::post('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    Route::get('/roles', [RoleController::class, 'index']);

    //API routes for PermissionController
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::get('permissions/{permission}', [PermissionController::class, 'show']);
    Route::post('permissions/{permission}', [PermissionController::class, 'update']);
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy']);
});

Route::get('/broadcast-test/{userId}', function ($userId) {
$payload = [
    'user_id' => $userId,
    'title' => '🔔 Test Notification',
    'message' => 'This is a test notification via Reverb',
    'url' => '/test',
    'date' => now()->toDateTimeString(),
    'id' => \Illuminate\Support\Str::uuid(),
];

event(new TestBroadcastEvent($payload));

return response()->json(['sent' => true]);
});
Route::middleware('auth:sanctum')->group(function () {

    Route::post('test-notification', function(){
        $userId = Auth::id();
        $user = User::where('id',$userId)->first();

        $user->notify(new TestRealTimeNotification());
        return ResponseHelper::success();
    });

    Route::prefix('contacts')->group(function () {
        Route::get('{id}/deals', [ContactController::class, 'deals']);
        Route::get('{id}/courses', [ContactController::class, 'courses']);
        Route::get('{id}/invoices', [ContactController::class, 'invoices']);
        Route::get('{id}/emails', [ContactController::class, 'emails']);
        Route::get('{id}/certificates', [ContactController::class, 'certificates']);
    });

    //switch Role
    Route::post('users/set-current-role', [UserController::class, 'setCurrentRole']);
    Route::get('users/get-user-roles', [UserController::class, 'allUserRoles']);

    //Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    Route::post('/profile/change-password',[ProfileController::class,'changePassword']);
    Route::post('/profile/update-image', [ProfileController::class,'updateImage']);
    Route::post('/update-profile', [IntegrationController::class, 'updateProfile']);
    Route::post('/change-password', [IntegrationController::class, 'changePassword']);
    Route::post('/update-image', [IntegrationController::class, 'updateImage']);

    //languages
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::post('/languages', [LanguageController::class, 'store']);
    Route::get('/languages/{language}', [LanguageController::class, 'show']);
    Route::post('/languages/{language}', [LanguageController::class, 'update']);
    Route::delete('/languages/{language}', [LanguageController::class, 'destroy']);

    // Dashboard
    Route::prefix("dashboard")->controller(DashboardController::class)->group(function () {
        Route::get('/statistics', 'getDashboardStatistics');
        Route::get('/course-by-start-date', 'getCourseByStartDate');
    });
     // EmailBuilder
     Route::prefix("email-builder")->controller(EmailBuilderController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/category', 'getCategories');
        Route::post('/send', 'sendEmails');
        Route::get('/{email}', 'show');
        Route::post('/{email}', 'update');
        Route::delete('/{email}', 'destroy');
    });
    // EmailBuilder
    Route::prefix("survey-builder")->controller(SurveyBuilderController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('/send', 'sendSurvey');
        Route::get('/{survey}', 'show');
        Route::post('/{survey}', 'update');
        Route::delete('/{survey}', 'destroy');
    });

    // Survey Category
    Route::apiResource('survey-categories',SurveyCategoryController::class);

    // survey-answers
    Route::prefix('survey-answers')->controller(SurveyBuilderAnswerController::class)->group(function () {
        Route::get('/{surveyId}', 'getAnswers');
    });

    // Action History
    Route::prefix("action-history")->controller(ActionHistoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{actionHistory}', 'show');
    });

    //posts from external api
    Route::get('/posts', [BlogController::class, 'list_posts']);
    //events from external api
    Route::get('/events', [BlogController::class, 'list_events']);
    //news from external api
    Route::get('/news', [BlogController::class, 'list_news']);


    // Invitation
    Route::get('/invitation', [InvitationController::class, 'index']);
    Route::post('/invitation', [InvitationController::class, 'create']);


    //audit
    Route::get('/audit-logs', [AuditController::class, 'index']);
    // Route::get('/audit-logs/{model}', [AuditController::class, 'show']);


    //Forms
    Route::get('/designed-forms', [DesignedFormController::class, 'index']);
    Route::post('/get-form-class', [DesignedFormController::class, 'getFormByClassId']);
    Route::get('/get-forms-user', [DesignedFormController::class, 'getFormUser']);
    Route::post('/designed-forms', [DesignedFormController::class, 'store']);
    Route::post('/send-designed-forms', [DesignedFormController::class, 'sendForm']);
    Route::get('/get-form-not-answered', [DesignedFormController::class, 'getFormNotAnswered']);
    Route::get('/get-answered-form', [DesignedFormController::class, 'getAnsweredForm']);
    Route::post('/designed-forms/{form}', [DesignedFormController::class, 'update']);
    Route::delete('/designed-forms/{form}', [DesignedFormController::class, 'destroy']);
    Route::post('/rate-answered-form', [DesignedFormController::class, 'addSurveyRate']);

    //questions
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::post('/questions/{question}', [QuestionController::class, 'update']);
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);

    //answers
    Route::get('/answers', [AnswerController::class, 'index']);
    Route::post('/answers', [AnswerController::class, 'store']);
    // Route::post('/answers/{answer}', [AnswerController::class, 'update']);
    Route::delete('/answers/{answer}', [AnswerController::class, 'destroy']);

    //file
    Route::post('/upload-file', [FileController::class, 'uploadFile']);
    Route::get('/delete-file', [FileController::class, 'deleteFile']);

    // matching
    Route::post('/match-course', [CourseMatchingController::class, 'match']);
    Route::get('/get-categories-by-source', [CourseMatchingController::class, 'get_categories_by_source']);
    Route::get('/get-courses', [CourseMatchingController::class, 'get_courses']);
    Route::get('/get-matches', [CourseMatchingController::class, 'getMatches']);
    Route::post('courses/{course}/undo-matching', [CourseMatchingController::class, 'undoMatching']);
    Route::get('/course-match-actions', [CourseMatchUserActionController::class, 'index']);



    //notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);

    //announcement
    Route::post('send-course-announcement', [AnnouncementController::class, 'sendCourseAnnouncements']);
    Route::post('instructor/availability', [AnnouncementController::class, 'setAvailabilitybyInstructor']);
    Route::post('get-available-instructor', [AnnouncementController::class, 'getAvailableInstructors']);
    Route::get('get-course-announcement', [AnnouncementController::class, 'getCourseAnnouncement']);
    Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'destroy']);
    Route::get('get-instructor-announcement', [AnnouncementController::class, 'getInstructorAnnouncement']);
    //notes
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/notes/{note}', [NoteController::class, 'show']);
    Route::post('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
    Route::get('/notes', [NoteController::class, 'index']);
    Route::get('/sended-notes', [NoteController::class, 'SendedNotes']);
    Route::post('/ack-note', [NoteController::class, 'ackNote']);
    //guest survey
    Route::get('/guest-survey', [GuestSurveyController::class, 'index']);


    Route::get('/get-encrypted-token', action: [TokenController::class, 'getToken']);


    //reports
    Route::prefix('reports')->group(function () {
        Route::get('employees-under-account/{companyId}', [ReportController::class, 'employeesUnderAccount']);
        Route::get('attended-courses/{courseId}', [ReportController::class, 'attendedCourses']);
        Route::get('total-hours-attended/{userId}', [ReportController::class, 'totalHoursAttended']);
        Route::get('certificates-obtained/{userId}', [ReportController::class, 'certificatesObtained']);
        Route::get('certificates-obtained-by-course/{courseId}', [ReportController::class, 'certificatesObtainedByCourse']);

        //getCoursesEvaluations
        Route::get('courses-surveys-evaluation', [ReportController::class, 'getCoursesEvaluations']);
        Route::get('rating-attendance', [ReportController::class, 'ratingsBasedOnAttendance']);

        Route::get('quiz-taken/{userId}', [ReportController::class, 'quizzesTaken']);
        Route::get('course-cities/{userId}', [ReportController::class, 'courseCities']);
        Route::get('trainees-per-period', [ReportController::class, 'traineesPerPeriod']);
        Route::get('courses-taken/{userId}', [ReportController::class, 'coursesTaken']);
        Route::get('best-reviewed-courses', [ReportController::class, 'bestReviewedCourses']);
        Route::get('total-training-hours/{userId}', [ReportController::class, 'totalTrainingHours']);

        Route::get('sessions-courses', [ReportController::class, 'getSessionsReport']);
        Route::get('sessions-courses/{id}', [ReportController::class, 'getCourseReport']);

        Route::get('sessions-courses-advanced', [ReportController::class, 'getCoursesReportByIndividualOrTeam']);

    });

});


    Route::get('instructor/availability', [AnnouncementController::class, 'setAvailability'])->name('instructor.availability');
    Route::post('/get-form-by-id', [DesignedFormController::class, 'getFormById']);

    Route::post('/get-form-by-slug', [DesignedFormController::class, 'getFormBySlug']);

    Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);

    //answer guest survey
    Route::post('/answer-survey', [GuestSurveyController::class, 'store']);





// ->middleware('check.client');
