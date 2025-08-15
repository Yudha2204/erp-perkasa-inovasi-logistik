<?php

use Illuminate\Support\Facades\Route;
use Modules\FinanceKas\App\Http\Controllers\FinanceKasController;
use Modules\FinanceKas\App\Http\Controllers\PembayaranController;
use Modules\FinanceKas\App\Http\Controllers\PenerimaanController;
use Modules\FinanceKas\App\Http\Controllers\TransactionsKasInController;
use Modules\FinanceKas\App\Http\Controllers\TransactionsKasOutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth','verified']], function () {
    Route::prefix('finance')->name('finance.kas.')->group(function () {
        Route::get('/kas', [FinanceKasController::class, 'index'])->name('index');

        Route::resource('/kas/penerimaan', PenerimaanController::class);
        Route::resource('/kas/pembayaran', PembayaranController::class);

        Route::resource('/kas/transaction-kas-out', TransactionsKasOutController::class);
        Route::resource('/kas/transaction-kas-in', TransactionsKasInController::class);

        Route::get('/kas/pembayaran/{id}/jurnal', [PembayaranController::class, 'getJurnal'])->name('pembayaran.jurnal');
        Route::get('/kas/penerimaan/{id}/jurnal', [PenerimaanController::class, 'getJurnal'])->name('penerimaan.jurnal');

        Route::get('/kas/get-job-order', [FinanceKasController::class, 'getJobOrder'])->name('job-order');
        Route::get('/kas/get-job-order-details', [FinanceKasController::class, 'getJobOrderDetails'])->name('job-order.details');
    });
});
