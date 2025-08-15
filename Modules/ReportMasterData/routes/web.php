<?php

use Illuminate\Support\Facades\Route;
use Modules\ReportMasterData\App\Http\Controllers\ReportMasterDataController;

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
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [ReportMasterDataController::class, 'index'])->name('index'); 
        Route::get('/international', [ReportMasterDataController::class, 'getDataInternational'])->name('getDataInternational');
        Route::get('/domestic', [ReportMasterDataController::class, 'getDataDomestic'])->name('getDataDomestic');

        Route::get('/bar-data', [ReportMasterDataController::class, 'getBarData'])->name('getBarData');

        Route::get('/get-by-date', [ReportMasterDataController::class, 'getDataByDate'])->name('getDataByDate');
    });
});

