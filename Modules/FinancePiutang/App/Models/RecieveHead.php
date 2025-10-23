<?php

namespace Modules\FinancePiutang\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinancePiutang\Database\factories\SalesOrderHeadFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Spatie\Permission\Traits\HasRoles;

class RecieveHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'receive_payment_head';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(RecieveDetail::class, 'head_id', 'id');
    }
    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }
    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
    public function getTransactionAttribute()
    {
        $date = $this->date_recieve;
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $number = sprintf('%04d', $this->number);

        return sprintf("RVR.%s-%02d-%04d", $year, $month, $number);
    }
    public function getTotalAttribute()
    {
        $detail = RecieveDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        return $total;
    }
    public function getDiscountAttribute()
    {
        $detail = RecieveDetail::where('head_id', $this->id)->get();
        $discount = 0;
        foreach($detail as $d) {
            $discount += $d->discount;
        }

        return $discount;
    }
    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 4)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', $this->currency_id)
                    ->get();
        return $jurnal;
    }

    public function getJobOrderAttribute()
    {
        if($this->job_order_id) {
            if($this->source === "export") {
                return MarketingExport::find($this->job_order_id);
            } else if($this->source === "import") {
                return MarketingImport::find($this->job_order_id);
            }
        }
        return null;
    }

    public function getDpAttribute()
    {
        $dp = 0;
        $detail = RecieveDetail::where('head_id', $this->id)->get();
        foreach($detail as $d) {
            if($d->dp) {
                $dp += $d->dp;
            }
        }
        return $dp;
    }

    protected $appends = ['transaction','jurnal','job_order','discount', 'dp'];
}
