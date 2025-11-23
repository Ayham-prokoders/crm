<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use Modules\RegisterManagement\Http\Controllers\RegisterRequestController;
use Modules\RegisterManagement\Http\Controllers\RegisterManagementController;

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

Route::post('/webhooks/new-form', [WebhookController::class, 'handleNewForm']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('registermanagement', RegisterManagementController::class)->names('registermanagement');

    //register-request
    Route::post('/register-request/sync-from-website', [RegisterRequestController::class, 'syncRegisterRequest']);
    Route::get('/register-request/all', [RegisterRequestController::class, 'index']);
    Route::post('/register-request/approve/{registration}', [RegisterRequestController::class, 'approve']);
    Route::post('/register-request/cancel/{registration}', [RegisterRequestController::class, 'cancelRequest']);
    Route::post('/register/info', [RegisterRequestController::class, 'getRegisterInfo']);
    Route::get('/register-request-chart', [RegisterRequestController::class, 'getRegisterRequestChart']);


    Route::prefix('forms')->group(function () {
        Route::get('/sync', [RegisterRequestController::class, 'syncForms']);
        Route::post('/export', [RegisterRequestController::class, 'exportForms']);
        Route::get('/stats', [RegisterRequestController::class, 'getFormsStats']);
        Route::post('/manage-sync', [RegisterRequestController::class, 'manageSync']);
    });

});
