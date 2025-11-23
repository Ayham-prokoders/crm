<?php

use Illuminate\Support\Facades\Route;
use Modules\TrainerManagement\Http\Controllers\{TrainerAttendanceController ,TopicController ,TrainerSignatureController
,InstructorController};

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

Route::middleware('auth:sanctum')->group(function () {

     //topics
    Route::prefix("topics")->group(function () {
       Route::controller(TopicController::class)
       ->group(function () {
       Route::get('/', 'index');
       Route::post('/', 'store');
       Route::get('/{topic}', 'show');
       Route::post('/{topic}', 'update');
       Route::delete('/{topic}', 'destroy');
       });
   });
  
     //Trainer Attendance
     Route::prefix("attendance")->group(function () {
        Route::controller(TrainerAttendanceController::class)
        ->group(function () {
        Route::get('/trainer/all', 'get');
        Route::post('/trainer/save', 'store');
        });
    });
     
     //trainer signature on trainee-attendance
     Route::prefix("trainer-signature")->group(function () {
        Route::controller(TrainerSignatureController::class)
        ->group(function () {
        Route::post('/save', 'saveTrainerSignature');
        Route::get('/get', 'getTrainerSignature');
        });
    });
         
    //instructors
    Route::prefix("instructors")->group(function () {
        Route::controller(InstructorController::class)
        ->group(function () {
        Route::get('/all', 'index');
        
        // Route::post('/create', 'updateOrCreate');
        Route::post('/create', 'create');
        Route::post('/update/{instructor}', 'update');
        Route::delete('/delete/{instructor}', 'delete');
        Route::post('/{instructorId}/generate-cv', 'generateCV');
        Route::post('/add-rating', 'addRating');
        
        });
    });
    Route::post('/get-instructor', [InstructorController::class,'find']);
    Route::post('/get-instructor-by-id', [InstructorController::class,'findInstructorById']);
    Route::post('/get-instructor-by-location', [InstructorController::class,'findByLocation']);
    Route::post('/set-instructor', [InstructorController::class,'setInstructor']);

});
Route::post('/get-instructor', [InstructorController::class,'find']);
