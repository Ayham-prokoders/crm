<?php

use Illuminate\Support\Facades\Route;
use Modules\TaskManagement\Http\Controllers\BoardTaskController;
use Modules\TaskManagement\Http\Controllers\TaskManagementController;
use Modules\TaskManagement\Http\Controllers\{TaskController ,ExternalTaskController, CommentController ,ContactDirectoryController};

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('taskmanagement', TaskManagementController::class)->names('taskmanagement');
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('tasks')->controller(TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('list', 'list');
        Route::get('statistics', 'statistics');
        Route::get('days-with-tasks', 'daysWithTasks');
        Route::post('/', 'store');
        Route::get('{task}', 'show');
        Route::put('{task}', 'update');
        Route::delete('{task}', 'destroy');
    });

    Route::prefix('external-tasks')->controller(ExternalTaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('days-with-tasks', 'daysWithTasks');
        Route::post('/', 'store');
        Route::get('{task}', 'show');
        Route::put('{task}', 'update');
        Route::delete('{task}', 'destroy');
    });
    Route::prefix('{type}/{id}')
        ->where(['type' => 'tasks|external-tasks|board-tasks'])
        ->controller(CommentController::class)
        ->group(function () {
            Route::post('/comments', 'store');
            Route::get('/comments', 'index');
    });

    Route::prefix('board-tasks')->group(function () {
        Route::get('/', [BoardTaskController::class, 'index']);           
        Route::post('/', [BoardTaskController::class, 'store']);          
        Route::get('{board_task}', [BoardTaskController::class, 'show']); 
        Route::put('{board_task}', [BoardTaskController::class, 'update']); 
        Route::delete('{board_task}', [BoardTaskController::class, 'destroy']); 
    });
    Route::patch('board-tasks/{board_task}/status', [BoardTaskController::class, 'changeStatus']);


    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // ContactDirectory
    Route::prefix("contact")->controller(ContactDirectoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{contact}', 'show');
        Route::post('/{contact}', 'update');
        Route::delete('/{contact}', 'destroy');
    });
});
