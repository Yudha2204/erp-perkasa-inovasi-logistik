<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\App\Http\Controllers\SettingController;

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

Route::group([], function () {
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index'); 
        Route::put('/setting/logo', [SettingController::class, 'update'])->name('logo.update');
    });
});
