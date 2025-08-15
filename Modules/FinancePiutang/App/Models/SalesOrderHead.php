<?php

namespace Modules\FinancePiutang\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinancePiutang\Database\factories\SalesOrderHeadFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Spatie\Permission\Traits\HasRoles;

class SalesOrderHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'sales_order_head';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'head_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }

    public function getTransactionAttribute()
    {
        $date = $this->date;
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $number = sprintf('%04d', $this->number);

        return sprintf("SO-PIL%s-%02d-%04d", $year, $month, $number);
    }

    public function getMarketingAttribute()
    {
        if($this->marketing_id) {
            if($this->source === "export") {
                return MarketingExport::with('quotation')->find($this->marketing_id);
            } else if($this->source === "import") {
                return MarketingImport::with('quotation')->find($this->marketing_id);
            }
        }
        return null;
    }

    public function getTotalAttribute()
    {   
        $discount = $this->discount_nominal;
        $detail = SalesOrderDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            return $total-(($discount/100)*$total);
        }
        return $total-$discount;
    }

    public function getDiscountAttribute()
    {
        $discount = $this->discount_nominal;
        $detail = SalesOrderDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            return ($discount/100)*$total;
        }
        return $discount;
    }

    protected $appends = ['transaction', 'total', 'discount'];
}
