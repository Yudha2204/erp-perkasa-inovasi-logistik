<?php

namespace Modules\FinancePayments\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Spatie\Permission\Traits\HasRoles;

class PaymentHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'payment_head';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(PaymentDetail::class, 'head_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(MasterContact::class, 'vendor_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(MasterContact::class, 'customer_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }

    public function getTransactionAttribute()
    {
        $date = $this->date_payment;
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $number = sprintf('%04d', $this->number);

        return sprintf("PVP.%s-%02d-%04d", $year, $month, $number);
    }

    public function getTotalAttribute()
    {
        $detail = PaymentDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        return $total;
    }
    public function getDiscountAttribute()
    {
        $detail = PaymentDetail::where('head_id', $this->id)->get();
        $discount = 0;
        foreach($detail as $d) {
            $discount += $d->discount;
        }

        return $discount;
    }
    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 8)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', $this->currency_id)
                    ->get();
        return $jurnal;
    }

    public function getJobOrderAttribute()
    {
        if ($this->job_order_id) {
            if ($this->source === "import") {
                return OperationImport::with('marketing')->find($this->job_order_id);
            } else if ($this->source === "export") {
                return OperationExport::with('marketing')->find($this->job_order_id);
            }
        }
        return null;
    }

    public function getDpAttribute()
    {
        $dp = 0;
        $detail = PaymentDetail::where('head_id', $this->id)->get();
        foreach($detail as $d) {
            if($d->dp) {
                $dp += $d->dp;
            }
        }
        return $dp;
    }

    protected $appends = ['transaction','jurnal','job_order','discount', 'dp'];
}
