<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\App\Http\Controllers\MarketingController;
use Modules\Marketing\App\Http\Controllers\MarketingExportController;
use Modules\Marketing\App\Http\Controllers\MarketingImportController;
use Modules\Marketing\App\Http\Controllers\MarketingOverviewController;
use Modules\Marketing\App\Http\Controllers\MarketingReportController;

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

// Route::group([], function () {
//     Route::resource('marketing', MarketingController::class)->names('marketing');
// });

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::prefix('marketing')->name('marketing.')->group(function () {

        Route::resource('overview', MarketingOverviewController::class);
        
        Route::get('export/create-quotation', [MarketingExportController::class, 'createQuotation'])->name('export.create-quotation');
        Route::get('export/get-data-customer', [MarketingExportController::class, 'getDataCustomer'])->name('export.getDataCustomer');
        Route::post('export/store-quotation', [MarketingExportController::class, 'storeQuotation'])->name('export.store-quotation');
        Route::get('export/edit-quotation/{id}', [MarketingExportController::class, 'editQuotation'])->name('export.edit-quotation');
        Route::get('export/show-quotation/{id}', [MarketingExportController::class, 'showQuotation'])->name('export.show-quotation');
        Route::post('export/update-dimension', [MarketingExportController::class, 'updateDimension'])->name('export.update-dimension');
        Route::post('export/update-quotation', [MarketingExportController::class, 'updateQuotation'])->name('export.update-quotation');
        Route::post('export/delete-quotation', [MarketingExportController::class, 'deleteQuotation'])->name('export.delete-quotation');
        Route::post('export/delete-document', [MarketingExportController::class, 'deleteDocument'])->name('export.delete-document');
        Route::resource('export', MarketingExportController::class);
        

        Route::get('import/create-quotation', [MarketingImportController::class, 'createQuotation'])->name('import.create-quotation');
        Route::get('import/get-data-customer', [MarketingImportController::class, 'getDataCustomer'])->name('import.getDataCustomer');
        Route::post('import/store-quotation', [MarketingImportController::class, 'storeQuotation'])->name('import.store-quotation');
        Route::get('import/edit-quotation/{id}', [MarketingImportController::class, 'editQuotation'])->name('import.edit-quotation');
        Route::get('import/show-quotation/{id}', [MarketingImportController::class, 'showQuotation'])->name('import.show-quotation');
        Route::post('import/update-dimension', [MarketingImportController::class, 'updateDimension'])->name('import.update-dimension');
        Route::post('import/update-quotation', [MarketingImportController::class, 'updateQuotation'])->name('import.update-quotation');
        Route::post('import/delete-quotation', [MarketingImportController::class, 'deleteQuotation'])->name('import.delete-quotation');
        Route::post('import/delete-document', [MarketingImportController::class, 'deleteDocument'])->name('import.delete-document');
        Route::resource('import', MarketingImportController::class);

        Route::get('report/export-pdf', [MarketingReportController::class, 'exportPdf'])->name('report.export-pdf');
        Route::resource('report', MarketingReportController::class);

    });        
});
