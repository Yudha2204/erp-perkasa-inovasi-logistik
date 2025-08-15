<?php

use Illuminate\Support\Facades\Route;
use Modules\Operation\App\Http\Controllers\OperationController;
use Modules\Operation\App\Http\Controllers\OperationExportController;
use Modules\Operation\App\Http\Controllers\OperationImportController;
use Modules\Operation\App\Http\Controllers\OperationReportController;

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
    Route::prefix('operation')->name('operation.')->group(function () {


        Route::get('export/create-progress', [OperationExportController::class, 'createProgress'])->name('export.create-progress');
        Route::post('export/store-progress', [OperationExportController::class, 'storeProgress'])->name('export.store-progress');
        Route::get('export/edit-progress', [OperationExportController::class, 'editProgress'])->name('export.edit-progress');
        Route::post('export/update-progress', [OperationExportController::class, 'updateProgress'])->name('export.update-progress');
        Route::delete('export/delete-progress/{id}', [OperationExportController::class, 'deleteProgress'])->name('export.delete-progress');
        Route::delete('export/delete-progress-document/{id}', [OperationExportController::class, 'deleteProgressDocument'])->name('export.delete-progress-document');
        Route::post('export/delete-activity-document/', [OperationExportController::class, 'deleteActivityDocument'])->name('export.delete-activity-document');
        Route::post('export/delete-arrival-document/', [OperationExportController::class, 'deleteArrivalDocument'])->name('export.delete-arrival-document');
        Route::resource('export', OperationExportController::class);

        Route::get('import/create-progress', [OperationImportController::class, 'createProgress'])->name('import.create-progress');
        Route::post('import/store-progress', [OperationImportController::class, 'storeProgress'])->name('import.store-progress');
        Route::get('import/edit-progress', [OperationImportController::class, 'editProgress'])->name('import.edit-progress');
        Route::post('import/update-progress', [OperationImportController::class, 'updateProgress'])->name('import.update-progress');
        Route::delete('import/delete-progress/{id}', [OperationImportController::class, 'deleteProgress'])->name('import.delete-progress');
        Route::delete('import/delete-progress-document/{id}', [OperationImportController::class, 'deleteProgressDocument'])->name('import.delete-progress-document');
        Route::post('import/delete-activity-document/', [OperationImportController::class, 'deleteActivityDocument'])->name('import.delete-activity-document');
        Route::post('import/delete-arrival-document/', [OperationImportController::class, 'deleteArrivalDocument'])->name('import.delete-arrival-document');
        Route::resource('import', OperationImportController::class);

        Route::get('report/export-pdf', [OperationReportController::class, 'exportPdf'])->name('report.export-pdf');
        Route::resource('report', OperationReportController::class);
            
    });
});