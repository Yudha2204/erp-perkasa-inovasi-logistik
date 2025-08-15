<?php

use Illuminate\Support\Facades\Route;
use Modules\RealtimeTracking\App\Http\Controllers\RealtimeTrackingController;

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
    Route::get('realtime-tracking', [RealtimeTrackingController::class, 'index'])->name('realtime-tracking.index');
    Route::get('realtime-tracking/{category}/{id}', [RealtimeTrackingController::class, 'detail'])->name('realtime-tracking.detail');
});
