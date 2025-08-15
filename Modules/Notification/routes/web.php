<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\App\Http\Controllers\NotificationController;

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
    Route::get('notification', [NotificationController::class, 'getNotification'])->name('notification');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
});
