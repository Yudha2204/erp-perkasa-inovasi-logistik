<?php

use Illuminate\Support\Facades\Route;
use Modules\ReportFinance\App\Http\Controllers\ReportFinanceController;

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

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::prefix('finance/report-finance')->name('finance.report-finance.')->group(function () {
        Route::get('/', [ReportFinanceController::class, 'index'])->name('index');
        Route::get('/data-ledger', [ReportFinanceController::class, 'BukuBesar'])->name('data-ledger');
        Route::get('/year-ledger', [ReportFinanceController::class, 'BukuBesarYear'])->name('year-data-ledger');
        Route::get('/year-trial-balancer', [ReportFinanceController::class, 'NeracaSaldoYear'])->name('year-trial-balancer');
        Route::get('/data-trial-balancer', [ReportFinanceController::class, 'NeracaSaldo'])->name('trial-balance');
        Route::get('/data-neraca', [ReportFinanceController::class, 'Neraca'])->name('neraca');
        Route::get('/year-neraca', [ReportFinanceController::class, 'NeracaYear'])->name('year-neraca');
        Route::get('/data-general-ledger', [ReportFinanceController::class, 'JurnalUmum'])->name('general-ledger');
        Route::get('/year-general-ledger', [ReportFinanceController::class, 'JurnalUmumYear'])->name('year-general-ledger');
        Route::get('/data-cash-flow', [ReportFinanceController::class, 'ArusKas'])->name('cash-flow');
        Route::get('/year-cash-flow', [ReportFinanceController::class, 'ArusKasYear'])->name('year-cash-flow');
        Route::get('/data-profit-loss', [ReportFinanceController::class, 'LabaRugi'])->name('profit-loss');
        Route::get('/year-profit-loss', [ReportFinanceController::class, 'LabaRugiYear'])->name('year-profit-loss');
        Route::get('/laporan-rekening', [ReportFinanceController::class, 'LaporanKeuangan'])->name('laporan-rekening');
        Route::get('/laporan-rekening-pdf/{id}', [ReportFinanceController::class, 'PrintLaporanKeuangan'])->name('laporan-rekening-pdf');
        Route::get('/outstanding-arap', [ReportFinanceController::class, 'OutstandingARAP'])->name('outstanding-arap');
    });
});
