<?php

use Illuminate\Support\Facades\Route;
use Modules\FinanceDataMaster\App\Http\Controllers\AccountDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\AccountTypeController;
use Modules\FinanceDataMaster\App\Http\Controllers\ContactDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\CurrencyDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\FinanceDataMasterController;
use Modules\FinanceDataMaster\App\Http\Controllers\TaxDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\TermOfPaymentDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\FiscalPeriodController;

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
    Route::prefix('finance')->name('finance.master-data.')->group(function () {
        Route::get('/master-data', [FinanceDataMasterController::class, 'index'])->name('index');

        Route::resource('/master-data/contact', ContactDataController::class);
        Route::resource('/master-data/currency', CurrencyDataController::class);
        Route::resource('/master-data/tax', TaxDataController::class);
        Route::get('/master-data/tax/get-accounts-by-type', [TaxDataController::class, 'getAccountsByType'])->name('tax.get-accounts-by-type');
        Route::resource('/master-data/term-of-payment', TermOfPaymentDataController::class);

        Route::post('master-data/account/store-beginning-balance', [AccountDataController::class, 'storeBeginningBalance'])->name('account.store-beginning-balance');
        Route::post('master-data/account/update-beginning-balance', [AccountDataController::class, 'updateBeginningBalance'])->name('account.update-beginning-balance');
        Route::resource('/master-data/account', AccountDataController::class);
        Route::resource('/master-data/account-type', AccountTypeController::class);

        Route::get('/master-data/get-account-data', [FinanceDataMasterController::class, 'getAccount'])->name('account');

        // Fiscal Period Management
        Route::prefix('master-data/fiscal-period')->name('fiscal-period.')->group(function () {
            // CRUD Routes
            Route::get('/', [FiscalPeriodController::class, 'index'])->name('index');
            Route::get('/create', [FiscalPeriodController::class, 'create'])->name('create');
            Route::post('/', [FiscalPeriodController::class, 'store'])->name('store');
            Route::post('/store-year', [FiscalPeriodController::class, 'storeYear'])->name('store-year');
            Route::get('/{id}', [FiscalPeriodController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [FiscalPeriodController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FiscalPeriodController::class, 'update'])->name('update');
            Route::delete('/{id}', [FiscalPeriodController::class, 'destroy'])->name('destroy');
            
            // Management routes (for open/close operations)
            Route::get('/management', [FiscalPeriodController::class, 'management'])->name('management');
            Route::post('/open', [FiscalPeriodController::class, 'open'])->name('open');
            Route::post('/close', [FiscalPeriodController::class, 'close'])->name('close');
            Route::post('/bulk-open', [FiscalPeriodController::class, 'bulkOpen'])->name('bulk-open');
            Route::post('/bulk-close', [FiscalPeriodController::class, 'bulkClose'])->name('bulk-close');
            Route::get('/status', [FiscalPeriodController::class, 'status'])->name('status');
            Route::get('/list', [FiscalPeriodController::class, 'list'])->name('list');
            Route::put('/notes', [FiscalPeriodController::class, 'updateNotes'])->name('update-notes');
            Route::get('/periods', [FiscalPeriodController::class, 'getAvailablePeriods'])->name('periods');
        });
    });
});
