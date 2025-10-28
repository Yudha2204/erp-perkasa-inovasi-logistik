<?php

namespace Modules\FinancePayments\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterTax;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Modules\Operation\App\Models\VendorOperationExport;
use Modules\Operation\App\Models\VendorOperationImport;
use Modules\ReportFinance\App\Models\Sao;
use Spatie\Permission\Traits\HasRoles;

class OrderHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'account_payable_head';
    protected $guarded = [];

    public function sao()
    {
        return $this->hasOne(Sao::class, 'order_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'head_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(MasterContact::class, 'vendor_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(MasterContact::class, 'customer_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }
    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
    public function ppnTax()
    {
        return $this->belongsTo(MasterTax::class, 'tax_id');
    }
    public function getJobOrderAttribute()
    {
        if ($this->operation_id) {
            if ($this->source === "import") {
                return OperationImport::with('marketing')->find($this->operation_id);
            } elseif ($this->source === "export") {
                return OperationExport::with('marketing')->find($this->operation_id);
            }
        }
        return null;
    }

    public function getVendorOperationAttribute()
    {
        if ($this->transit_via) {
            if ($this->source === "import") {
                return VendorOperationImport::find($this->transit_via);
            } elseif ($this->source === "export") {
                return VendorOperationExport::find($this->transit_via);
            }
        }
        return null;
    }

    public function getTotalAttribute()
    {
        $discount = $this->discount_nominal;
        $total = $this->details->sum('total');
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            $total = $total-(($discount/100)*$total);
        } else {
            $total = $total-$discount;
        }

        if ($this->ppnTax) {
            $total = $total + ($total * ($this->ppnTax->tax_rate / 100));
        }

        return $total;
    }

    public function getTotalTaxAttribute()
    {
        $this->details->load('tax_detail');
        return $this->details->where('tax_detail.account_id', '!=', null)->groupBy('tax_detail.account_id')->map(function ($group) {
            return $group->sum('tax');
        });
    }

    public function getDiscountAttribute()
    {
        $discount = $this->discount_nominal;
        $total = $this->details->sum('total');
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            return ($discount/100)*$total;
        }
        return $discount;
    }

    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 7)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', $this->currency_id)
                    ->get();
        return $jurnal;
    }

    public function getDpAttribute()
    {
        return $this->details->sum('dp');
    }

    public function getDpPaymentAttribute()
    {
        $dpFromPayment = 0;
        $dpPayment = PaymentDetail::where('payable_id', $this->id)
                        ->whereNotNull('dp_nominal')
                        ->get();
        foreach($dpPayment as $dp) {
            $dpFromPayment += $dp->dp;
        }
        return $dpFromPayment;
    }

    protected $appends = ['total', 'discount', 'jurnal', 'dp', 'job_order', 'vendor_operation', 'dp_payment', 'total_tax'];
}
