<?php

use Illuminate\Support\Facades\Route;
use Modules\FinanceDataMaster\App\Http\Controllers\AccountDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\AccountTypeController;
use Modules\FinanceDataMaster\App\Http\Controllers\ContactDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\CurrencyDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\FinanceDataMasterController;
use Modules\FinanceDataMaster\App\Http\Controllers\TaxDataController;
use Modules\FinanceDataMaster\App\Http\Controllers\TermOfPaymentDataController;

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
        Route::resource('/master-data/term-of-payment', TermOfPaymentDataController::class);

        Route::post('master-data/account/store-beginning-balance', [AccountDataController::class, 'storeBeginningBalance'])->name('account.store-beginning-balance');
        Route::resource('/master-data/account', AccountDataController::class);
        Route::resource('/master-data/account-type', AccountTypeController::class);

        Route::get('/master-data/get-account-data', [FinanceDataMasterController::class, 'getAccount'])->name('account');
    });
});
