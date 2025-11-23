<?php

use Illuminate\Support\Facades\Route;
use Modules\Lms\Http\Controllers\{ClassController ,CourseController ,CompanyController ,ContentController,ExternalCertificateController
,SessionController ,FeedbackController ,AttendanceController ,CityController ,CertificateController, ExternalCourseController
,ExternalScheduleController};

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/
Route::post('/update-category-code', [CourseController::class, 'updateCourseCode'])->middleware('hmac');
Route::post('/create-cms-course', [CourseController::class, 'createCourseFromCMS'])->middleware('hmac');

Route::get('/companies', [CompanyController::class, 'index']);

Route::middleware(['hmac'])->group(function () {
    Route::get('/all-cities', [CityController::class, 'cities']);
    Route::get('/all-categories', [CourseController::class, 'categories']);
    Route::get('/all-external-courses', [ExternalCourseController::class, 'getExternalCourses']);
    Route::get('/all-courses', [CourseController::class, 'getCourses']);
});

Route::middleware('auth:sanctum')->group(function () {

     // Companies
     Route::prefix("companies")->group(function () {
        Route::controller(CompanyController::class)
        ->group(function () {
        Route::post('/', 'store');
        Route::get('/{company}', 'show');
        Route::post('/{company}', 'update');
        Route::delete('/{company}', 'destroy');
        });
    });

     // Courses from external api
     Route::prefix("courses")->group(function () {
        Route::controller(CourseController::class)
        ->group(function () {
        Route::get('/', 'list');
        Route::get('/list', 'listWithDeal');
        Route::get('/{id}/trainers', 'getCourseTrainers');
        Route::get('/{id}/average-rating', 'getCourseAverageRating');
        });
    });
    Route::get('/categories', [CourseController::class, 'list_categories']);
    Route::post('/get-course', [CourseController::class, 'getRelatedCourse']);

    // External-courses
    Route::prefix('external-courses')->group(function () {
        Route::get('/', [ExternalCourseController::class, 'index']);
        Route::post('/', [ExternalCourseController::class, 'store']);
        Route::post('/{externalCourse}', [ExternalCourseController::class, 'update']);
        Route::get('/{externalCourse}', [ExternalCourseController::class, 'show']);
    });

     // Classes
     Route::prefix("classes")->group(function () {
        Route::controller(ClassController::class)
        ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{classe}', 'show');
        Route::post('/{classe}', 'update');
        Route::delete('/{classe}', 'destroy');
        });
    });
    Route::get('/get-trainees',[ClassController::class,'get_trainees']);
    Route::get('/trainees/{id}', [ClassController::class, 'getTraineeById']);
    Route::post('/get-schedule-classes',[ClassController::class,'get_schedule_classes']);
    Route::post('/get-schedule-sessions',[ClassController::class,'get_schedule_sessions']);
    Route::post('/get-trainees-count', [ClassController::class, 'get_trainee_counts']);
    Route::post('/get-class-attendance', [ClassController::class, 'getClassAttendance']);

     // Sessions
     Route::prefix("sessions")->group(function () {
        Route::controller(SessionController::class)
        ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{session}', 'show');
        Route::post('/{session}', 'update');
        Route::delete('/{session}', 'destroy');
        });
    });
    Route::post('/get-sessions-class', [SessionController::class, 'list']);
    Route::get('/get-sessions-user', [SessionController::class, 'getAllSession']);
    Route::get('/get-sessions-for-trainee', [SessionController::class, 'getTraineeSession']);
    Route::get('/get-sessions-for-trainer', [SessionController::class, 'getTrainerSession']);

     // Content
     Route::prefix("content")->group(function () {
        Route::controller(ContentController::class)
        ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{content}', 'show');
        Route::post('/{content}', 'update');
        Route::delete('/{content}', 'destroy');
        });
    });
     Route::post('/get-content-class', [ContentController::class, 'list']);

     //Feedback
     Route::prefix("feedback")->group(function () {
        Route::controller(FeedbackController::class)
        ->group(function () {

        Route::get('/', 'show');
        Route::post('/', 'store');
        Route::delete('/{feedback}', 'destroy');
        });
    });

      //  Attendance
      Route::prefix("attendance")->group(function () {
        Route::controller(AttendanceController::class)
        ->group(function () {
        Route::post('/all', 'index');
        Route::post('/create-or-update', 'store');
        Route::post('/update/{attendance}', 'update');
        Route::post('/add-signature', 'saveSignature');
        });
    });

     //cities
     Route::get('/cities', [CityController::class, 'getCities']);

     //certificates
     Route::prefix("certificates")->group(function () {
        Route::controller(CertificateController::class)
        ->group(function () {
        Route::post('/', 'store');
        Route::get('/{certificate}', 'show');
        Route::post('/{certificate}', 'update');
        Route::delete('/{certificate}', 'destroy');
        Route::get('/', 'index');
        });
    });
    Route::post('/show-certificates', [CertificateController::class, 'showInWebsite']);
    Route::get('/generate-certificate-id', [CertificateController::class, 'generateCertificateID']);

});
//getCertificate
Route::get('/list-certificates', [CertificateController::class, 'getCertificate']);

Route::prefix('external-certificates')->controller(ExternalCertificateController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{externalCertificate}', 'show');
    Route::post('/{externalCertificate}', 'update');
    Route::delete('/{externalCertificate}', 'destroy');
});


Route::prefix('external-schedules')->group(function () {
    Route::get('/', [ExternalScheduleController::class, 'index']);
    Route::post('/', [ExternalScheduleController::class, 'store']);
    Route::get('/{externalSchedule}', [ExternalScheduleController::class, 'show']);
    Route::put('/{externalSchedule}', [ExternalScheduleController::class, 'update']);
    Route::delete('/{externalSchedule}', [ExternalScheduleController::class, 'destroy']);
});
