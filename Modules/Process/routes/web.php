<?php

use Illuminate\Support\Facades\Route;
use Modules\Process\App\Http\Controllers\ProcessController;
use Modules\Process\App\Http\Controllers\ExchangeRevaluationController;
use Modules\Process\App\Http\Controllers\ProfitLossClosingController;
use Modules\Process\App\Http\Controllers\AnnualProfitLossClosingController;

// Combined Process Routes (Main Interface)
Route::prefix('process')->name('process.')->group(function () {
    Route::get('/', [ProcessController::class, 'index'])->name('index');
    Route::post('/execute', [ProcessController::class, 'executeProcesses'])->name('execute');
    Route::post('/check-status', [ProcessController::class, 'checkStatus'])->name('check-status');
    Route::get('/periods', [ProcessController::class, 'getAvailablePeriods'])->name('periods');
    Route::get('/years', [ProcessController::class, 'getAvailableYears'])->name('years');
});

// Legacy Individual Process Routes (for backward compatibility)
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

// Resource routes for backward compatibility
Route::resource('process', ProcessController::class)
    ->names('process')
    ->whereNumber('process')
    ->except(['show', 'create', 'store', 'edit', 'update', 'destroy']); // Exclude methods we don't need

