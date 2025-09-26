<?php

use Illuminate\Support\Facades\Route;
use Modules\Process\App\Http\Controllers\ProcessController;
use Modules\Process\App\Http\Controllers\ExchangeRevaluationController;
use Modules\Process\App\Http\Controllers\ProfitLossClosingController;
use Modules\Process\App\Http\Controllers\AnnualProfitLossClosingController;

// 1) Put the specific routes FIRST
Route::prefix('process/exchange-revaluation')->name('process.exchange-revaluation.')->group(function () {
    Route::get('/', [ExchangeRevaluationController::class, 'index'])->name('index');
    Route::post('/execute', [ExchangeRevaluationController::class, 'execute'])->name('execute');
    Route::get('/status', [ExchangeRevaluationController::class, 'status'])->name('status');
    Route::get('/periods', [ExchangeRevaluationController::class, 'getAvailablePeriods'])->name('periods');
});

// Profit & Loss Closing
Route::prefix('process/profit-loss-closing')->name('process.pl-closing.')->group(function () {
    Route::get('/', [ProfitLossClosingController::class, 'index'])->name('index');
    Route::post('/execute', [ProfitLossClosingController::class, 'execute'])->name('execute');
    Route::get('/status', [ProfitLossClosingController::class, 'status'])->name('status');
    Route::get('/periods', [ProfitLossClosingController::class, 'getAvailablePeriods'])->name('periods');
});

// Annual Profit & Loss Closing
Route::prefix('process/annual-profit-loss-closing')->name('process.annual-pl-closing.')->group(function () {
    Route::get('/', [AnnualProfitLossClosingController::class, 'index'])->name('index');
    Route::post('/execute', [AnnualProfitLossClosingController::class, 'execute'])->name('execute');
    Route::get('/status', [AnnualProfitLossClosingController::class, 'status'])->name('status');
    Route::get('/years', [AnnualProfitLossClosingController::class, 'getAvailableYears'])->name('years');
});

// 2) Then your process resource (constrain the wildcard)
Route::resource('process', ProcessController::class)
    ->names('process')
    ->whereNumber('process'); // or ->whereUuid('process') if you’re using UUIDs

// 3) If you keep a manual index, that’s fine too
Route::get('process', [ProcessController::class, 'index'])->name('process.index');

