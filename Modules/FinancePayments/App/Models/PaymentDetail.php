<?php

namespace Modules\FinancePayments\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Spatie\Permission\Traits\HasRoles;

class PaymentDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'payment_detail';
    protected $guarded = [];
    
    public function head()
    {
        return $this->belongsTo(PaymentHead::class, 'head_id', 'id');
    }

    public function payable()
    {
        return $this->belongsTo(OrderHead::class, 'payable_id', 'id');
    }

    public function currency_via()
    {
        return $this->belongsTo(ExchangeRate::class, 'currency_via_id', 'id');
    }

    public function getTotalAttribute()
    {   
        if ($this->charge_type === 'account') {
            $amount = $this->amount ?? 0;
            $discount = $this->discount_nominal;
            $discount_type = $this->discount_type;
            
            if($discount_type === "persen") {
                return $amount-(($discount/100)*$amount);
            }
            return $amount-$discount;
        }
        
        // Original payable logic
        $discount = $this->discount_nominal;
        $discount_type = $this->discount_type;
        $amount = $this->payable->total;
        if($this->currency_via_id) {
            $exchange = ExchangeRate::find($this->currency_via_id);
            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
            if($exchange->from_currency_id === $this->head->currency_id) {
                $pembagi = $exchange->from_nominal/$exchange->to_nominal;
            }
            $amount = $pembagi*$this->amount_via;
        }
        if($this->payable->dp) {
            $amount -= $this->payable->dp;
        }
        if($this->getDpPaymentBefore($this->head_id)) {
            $amount -= $this->getDpPaymentBefore($this->head_id);
        }

        if($discount_type === "persen") {
            return $amount-(($discount/100)*$amount);
        }
        return $amount-$discount;
    }

    public function getDiscountAttribute()
    {   
        if ($this->charge_type === 'account') {
            $amount = $this->amount ?? 0;
            $discount = $this->discount_nominal;
            $discount_type = $this->discount_type;
            
            if($discount_type === "persen") {
                return ($discount/100)*$amount;
            }
            return $discount;
        }
        
        // Original payable logic
        $discount = $this->discount_nominal;
        $discount_type = $this->discount_type;
        $amount = $this->payable->total;
        if($this->currency_via_id) {
            $exchange = ExchangeRate::find($this->currency_via_id);
            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
            if($exchange->from_currency_id === $this->head->currency_id) {
                $pembagi = $exchange->from_nominal/$exchange->to_nominal;
            }
            $amount = $pembagi*$this->amount_via;
        }
        if($this->payable->dp) {
            $amount -= $this->payable->dp;
        }
        if($this->getDpPaymentBefore($this->head_id)) {
            $amount -= $this->getDpPaymentBefore($this->head_id);
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

    public function getDpPaymentBefore($head) {
        $dpFromPayment = 0;
        $dpPayment = PaymentDetail::where('payable_id', $this->payable_id)
                        ->whereNotNull('dp_nominal')
                        ->whereHas('head', function($query) use ($head) {
                            $query->where('number', '<', $head);
                        })
                        ->get();
        foreach($dpPayment as $dp) {
            $dpFromPayment += $dp->dp;
        }
        return $dpFromPayment;
    }

    public function isAccountCharge()
    {
        return $this->charge_type === 'account';
    }
    
    public function isPayableCharge()
    {
        return $this->charge_type === 'payable';
    }

    protected $appends = ['total', 'discount','dp'];
}
