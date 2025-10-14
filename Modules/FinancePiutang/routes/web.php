<?php

use Illuminate\Support\Facades\Route;
use Modules\FinancePiutang\App\Http\Controllers\FinancePiutangController;
use Modules\FinancePiutang\App\Http\Controllers\InvoiceController;
use Modules\FinancePiutang\App\Http\Controllers\ReceivePaymentController;
use Modules\FinancePiutang\App\Http\Controllers\SalesOrderController;
use Modules\FinancePiutang\App\Http\Controllers\TransactionsInvoice;
use Modules\FinancePiutang\App\Http\Controllers\TransactionsRecieve;

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
    Route::prefix('finance')->name('finance.piutang.')->group(function () {
        Route::get('/piutang', [FinancePiutangController::class, 'index'])->name('index');
        Route::get('/piutang/transaction-sales-order', [SalesOrderController::class, 'getTransaction'])->name('transaction-sales-order');
        Route::get('/piutang/transaction-receive-payment', [ReceivePaymentController::class, 'getTransaction'])->name('transaction-receive-payment');
        
        Route::get('/piutang/get-job-order', [FinancePiutangController::class, 'getJobOrder'])->name('get-job-order');
        Route::get('/piutang/get-marketing', [FinancePiutangController::class, 'getMarketing'])->name('get-marketing');
        Route::get('/piutang/invoice/get-sales-order', [InvoiceController::class, 'getSalesOrder'])->name('invoice.get-sales-order');
        Route::get('/piutang/invoice/get-term-by-contact/{id}', [InvoiceController::class, 'getTermByContact'])->name('invoice.get-term-by-contact');
        Route::get('/piutang/receive/get-invoice', [ReceivePaymentController::class, 'getInvoice'])->name('receive.get-invoice');
        Route::get('/piutang/get-invoice-details', [FinancePiutangController::class, 'getInvoice'])->name('get-invoice-details');
        Route::get('/piutang/get-sales-order-details', [FinancePiutangController::class, 'getSalesOrder'])->name('get-sales-order-details');
        
        Route::get('/piutang/invoice/{id}/jurnal', [InvoiceController::class, 'getJurnal'])->name('invoice.jurnal');
        Route::post('/piutang/invoice/{id}/pdf', [InvoiceController::class, 'getPdf'])->name('invoice.pdf');
        Route::get('/piutang/receive-payment/{id}/jurnal', [ReceivePaymentController::class, 'getJurnal'])->name('receive-payment.jurnal');

        Route::resource('/piutang/sales-order', SalesOrderController::class)->middleware('check.transaction.date');
        Route::resource('/piutang/receive-payment', ReceivePaymentController::class)->middleware('check.transaction.date');  
        Route::resource('/piutang/invoice', InvoiceController::class)->middleware('check.transaction.date');
    });
});
