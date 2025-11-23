<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\DesignedFormController;
use Modules\TrainerManagement\Http\Controllers\InstructorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return response('', 200);
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/test-error', function () {
    throw new \Exception("test display error_id");
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

 //instructor profile
//  Route::get('/instructor/profile/url/{slug}', [InstructorController::class, 'getInstructorProfileUrl'])->name('instructor.profile.url');
 Route::get('/instructor/profile/pdf/{slug}', [InstructorController::class, 'getInstructorProfilePdf']);
 Route::get('/instructor/profile/{slug}', [InstructorController::class, 'getInstructorProfile'])->name('instructor.profile');
 //instructor profile pdf
 Route::get('/survey/pdf/{slug}', [DesignedFormController::class, 'getSurveyPdf']);

 Route::get('/survey/{slug}/preview', [DesignedFormController::class, 'previewSurveyPdf'])
    ->name('survey.preview');
 //survey
 Route::get('/survey/{slug}', [DesignedFormController::class, 'getSurvey'])->middleware('decrypt.token');;
//  Route::post('/survey/submit', [DesignedFormController::class, 'SubmitSurvey'])->name('survey.submit')->middleware('xss');
Route::post('/survey/submit', [DesignedFormController::class, 'SubmitSurvey'])
    ->name('survey.submit')
    ->middleware('xss');
