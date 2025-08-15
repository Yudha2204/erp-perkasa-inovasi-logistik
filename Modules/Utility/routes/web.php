<?php

use Illuminate\Support\Facades\Route;
use Modules\Utility\App\Http\Controllers\UserListController;
use Modules\Utility\App\Http\Controllers\UserRoleController;
use Modules\Utility\App\Http\Controllers\UtilityController;

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
    Route::prefix('utility')->name('utility.')->group(function () {
        Route::resource('user-role', UserRoleController::class);
        Route::resource('user-list', UserListController::class);
        Route::resource('user-utility', UtilityController::class);
        Route::get('user-permissions', [UtilityController::class, 'getUserPermission'])->name('user-permissions');
    });
});