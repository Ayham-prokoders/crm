<?php

use Illuminate\Support\Facades\Route;
use Modules\DealManagement\Http\Controllers\BankController;
use Modules\DealManagement\Http\Controllers\DealManagementController;
use Modules\DealManagement\Http\Controllers\DealController;
use Modules\DealManagement\Http\Controllers\InvoiceController;

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
    Route::get('/currencies', [DealController::class, 'getCurrencies']);
    Route::prefix('deals')->controller(DealController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('list', 'list');
        Route::get('get-deals-date', 'getDateOfDeal');
        Route::get('get-deal-by-date', 'getDealByDate');
        Route::get('get-by-name', 'getDealsByName');
        Route::get('/{deal}/trainees',  'getDealTrainees');

        Route::post('/', 'store');
        Route::get('{deal}', 'show');
        Route::put('{deal}', 'update');
        Route::delete('{deal}', 'destroy');
    });

    Route::prefix('invoices')->controller(InvoiceController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{invoice}', 'show');
        Route::post('submit/{invoice}', 'submitInvoice');
        Route::put('{invoice}', 'update');
        Route::delete('{invoice}', 'destroy');
    });

    Route::prefix('banks')->controller(BankController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('list', 'list');
        Route::post('/', 'store');
        Route::get('{bank}', 'show');
        Route::put('{bank}', 'update');
        Route::delete('{bank}', 'destroy');
    });

});
