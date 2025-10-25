<?php

use Illuminate\Support\Facades\Route;
use Modules\FinancePayments\App\Http\Controllers\FinancePaymentsController;
use Modules\FinancePayments\App\Http\Controllers\PurchaseOrderController;
use Modules\FinancePayments\App\Http\Controllers\PurchasePaymentController;
use Modules\FinancePayments\App\Http\Controllers\TransactionsPaymentController;

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
    Route::prefix('finance')->name('finance.payments.')->group(function () {
        Route::get('/payments', [FinancePaymentsController::class, 'index'])->name('index');
        Route::get('/payments/transaction-purchase-payment', [PurchasePaymentController::class, 'getTransaction'])->name('transaction-purchase-payment');

        Route::get('/payments/get-job-order', [FinancePaymentsController::class, 'getJobOrder'])->name('job-order');
        Route::get('/payments/get-job-order-details', [FinancePaymentsController::class, 'getJobOrderDetails'])->name('job-order.details');
        Route::get('/payments/get-order', [FinancePaymentsController::class, 'getOrder'])->name('order');
        Route::get('/payments/get-order-details', [FinancePaymentsController::class, 'getOrderDetails'])->name('order.details');

        Route::get('/payments/account-payable/{id}/jurnal', [PurchaseOrderController::class, 'getJurnal'])->name('account-payable.jurnal');
        Route::get('/payments/purchase-payment/{id}/jurnal', [PurchasePaymentController::class, 'getJurnal'])->name('purchase-payment.jurnal');

        Route::resource('/payments/account-payable', PurchaseOrderController::class)->middleware('check.transaction.date:true');
        Route::resource('/payments/purchase-payment', PurchasePaymentController::class)->middleware('check.transaction.date');
    });
});
