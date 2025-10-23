<?php

namespace Modules\FinancePiutang\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinancePiutang\Database\factories\SalesOrderDetailFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Spatie\Permission\Traits\HasRoles;
use Modules\FinanceDataMaster\App\Models\MasterAccount;

class RecieveDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'receive_payment_detail';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(RecieveHead::class, 'head_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(InvoiceHead::class, 'invoice_id', 'id');
    }
    
    public function isAccountCharge()
    {
        return $this->charge_type === 'account';
    }
    
    public function isInvoiceCharge()
    {
        return $this->charge_type === 'invoice';
    }

    public function currency_via()
    {
        return $this->belongsTo(ExchangeRate::class, 'currency_via_id', 'id');
    }
    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
    public function getTotalAttribute()
    {
        // Handle account-based charges
        if ($this->charge_type === 'account') {
            $amount = $this->amount ?? 0;
            $discount = $this->discount_nominal;
            $discount_type = $this->discount_type;
            
            if($discount_type === "persen") {
                return $amount-(($discount/100)*$amount);
            }
            return $amount-$discount;
        }
        
        // Handle invoice-based charges (existing logic)
        $discount = $this->discount_nominal;
        $discount_type = $this->discount_type;
        $amount = $this->invoice->total;
        if($this->currency_via_id) {
            $exchange = ExchangeRate::find($this->currency_via_id);
            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
            if($exchange->from_currency_id === $this->head->currency_id) {
                $pembagi = $exchange->from_nominal/$exchange->to_nominal;
            }
            $amount = $pembagi*$this->amount_via;
        }
        if($this->invoice->dp) {
            $amount -= $this->invoice->dp;
        }
        if($this->getDpReceiveBefore($this->head_id)) {
            $amount -= $this->getDpReceiveBefore($this->head_id);
        }

        if($discount_type === "persen") {
            return $amount-(($discount/100)*$amount);
        }
        return $amount-$discount;
    }

    public function getDiscountAttribute()
    {
        $discount = $this->discount_nominal;
        $discount_type = $this->discount_type;
        
        // Handle account-based charges
        if ($this->charge_type === 'account') {
            $amount = $this->amount ?? 0;
            if($discount_type === "persen") {
                return ($discount/100)*$amount;
            }
            return $discount;
        }
        
        // Handle invoice-based charges (existing logic)
        $amount = $this->invoice->total;
        if($this->currency_via_id) {
            $exchange = ExchangeRate::find($this->currency_via_id);
            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
            if($exchange->from_currency_id === $this->head->currency_id) {
                $pembagi = $exchange->from_nominal/$exchange->to_nominal;
            }
            $amount = $pembagi*$this->amount_via;
        }
        if($this->invoice->dp) {
            $amount -= $this->invoice->dp;
        }
        if($this->getDpReceiveBefore($this->head_id)) {
            $amount -= $this->getDpReceiveBefore($this->head_id);
        }

        if($discount_type === "persen") {
            return ($discount/100)*$amount;
        }
        return $discount;
    }

    public function getDpAttribute() {
        $dp = $this->dp_nominal;
        if($this->dp_type === "persen") {
            return ($dp/100)*$this->getTotalAttribute();
        }
        return $dp;
    }

    public function getDpReceiveBefore($head) {
        $dpFromReceive = 0;
        $dpReceive = RecieveDetail::where('invoice_id', $this->invoice_id)
                        ->whereNotNull('dp_nominal')
                        ->whereHas('head', function($query) use ($head) {
                            $query->where('number', '<', $head);
                        })
                        ->get();
        foreach($dpReceive as $dp) {
            $dpFromReceive += $dp->dp;
        }
        return $dpFromReceive;
    }

    protected $appends = ['total', 'discount', 'dp'];
}
