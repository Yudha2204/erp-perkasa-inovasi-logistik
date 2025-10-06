<?php

use Illuminate\Support\Facades\Route;
use Modules\GeneralLedger\App\Http\Controllers\GeneralLedgerController;
use Modules\GeneralLedger\App\Http\Controllers\GeneralJournalController;

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
    Route::resource('generalledger', GeneralLedgerController::class)->names('generalledger');
    Route::resource('general-journal', GeneralJournalController::class)->names('generalledger.general-journal');
    Route::get('general-journal/transaction-number', [GeneralJournalController::class, 'getTransactionNumber'])->name('generalledger.general-journal.transaction-number');
    Route::get('general-journal/{id}/jurnal', [GeneralJournalController::class, 'getJurnal'])->name('generalledger.general-journal.jurnal');
});
