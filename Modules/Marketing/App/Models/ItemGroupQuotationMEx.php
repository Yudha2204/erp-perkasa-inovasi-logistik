<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\ItemGroupQuotationMExFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ItemGroupQuotationMEx extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'item_group_quotation_m_ex';
    protected $guarded = [];

    public function group_quotation_m_ex()
    {
        return $this->belongsTo(GroupQuotationMEx::class, 'group_quotation_m_ex_id','id');
    }
}
