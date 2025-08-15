<?php

namespace Modules\FinancePiutang\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterTax;
use Spatie\Permission\Traits\HasRoles;

class InvoiceDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'invoice_detail';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(InvoiceHead::class, 'head_id', 'id');
    }

    public function tax_detail()
    {
        return $this->belongsTo(MasterTax::class, 'tax_id', 'id');
    }

    public function getDiscountAttribute()
    {
        $discount = $this->discount_nominal;
        $price = $this->price;
        $quantity = $this->quantity;
        $total = $price*$quantity;

        if($this->discount_type === "persen") {
            return ($discount/100)*$total;
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

    public function getTotalAttribute()
    {
        $discount = $this->discount_nominal;
        $price = $this->price;
        $quantity = $this->quantity;
        $total = $price*$quantity;

        if($this->discount_type === "persen") {
            $total = $total-(($discount/100)*$total);
        } else {
            $total = $total-$discount;
        }

        if($this->tax_id) {
            $tax = MasterTax::find($this->tax_id);
            return $total-(($tax->tax_rate/100)*$total);
        }
        return $total;
    }

    public function getTaxAttribute()
    {
        $discount = $this->discount_nominal;
        $price = $this->price;
        $quantity = $this->quantity;
        $total = $price*$quantity;

        if($this->discount_type === "persen") {
            $total = $total-(($discount/100)*$total);
        } else {
            $total = $total-$discount;
        }

        if($this->tax_id) {
            $tax = MasterTax::find($this->tax_id);
            return ($tax->tax_rate/100)*$total;
        }
        return 0;
    }

    protected $appends = ['discount','total','tax', 'dp'];
} 
