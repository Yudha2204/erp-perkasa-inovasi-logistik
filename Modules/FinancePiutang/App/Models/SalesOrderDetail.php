<?php

namespace Modules\FinancePiutang\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinancePiutang\Database\factories\SalesOrderDetailFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class SalesOrderDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'sales_order_detail';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(SalesOrderHead::class, 'head_id', 'id');
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

    public function getTotalAttribute()
    {
        $discount = $this->discount_nominal;
        $price = $this->price;
        $quantity = $this->quantity;
        $total = $price*$quantity;

        if($this->discount_type === "persen") {
            return $total-(($discount/100)*$total);
        }
        return $total-$discount;
    }

    protected $appends = ['discount','total'];
}
