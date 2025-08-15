<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\BalanceAccountFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceKas\App\Models\KasInHead;
use Modules\FinanceKas\App\Models\KasOutHead;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePayments\App\Models\PaymentHead;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\RecieveHead;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Spatie\Permission\Traits\HasRoles;

class BalanceAccount extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'balance_account_data';
    protected $guarded = [];

    public function master_account()
    {
        return $this->belongsTo(MasterAccount::class, 'master_account_id', 'id');
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }

    public function getTransaction()
    {
        $transaksi = null;
        if($this->transaction_type_id === 2) {
            $transaksi = SalesOrderHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 3) {
            $transaksi = InvoiceHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 4) {
            $transaksi = RecieveHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 5) {
            $transaksi = KasOutHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 6) {
            $transaksi = KasInHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 7) {
            $transaksi = OrderHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 8) {
            $transaksi = PaymentHead::find($this->transaction_id);
        }

        return $transaksi;
    }
}
