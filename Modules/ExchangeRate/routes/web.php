<?php

use Illuminate\Support\Facades\Route;
use Modules\ExchangeRate\App\Http\Controllers\ExchangeRateController;

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
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::resource('exchange-rate', ExchangeRateController::class);
        Route::get('exhchange-rate-by-date', [ExchangeRateController::class, 'getExchangeByDate'])->name('exchange-by-date');
    });
});
