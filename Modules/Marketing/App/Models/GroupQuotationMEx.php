<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\GroupQuotationMExFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class GroupQuotationMEx extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'group_quotation_m_ex';
    protected $guarded = [];

    public function quotation_m_ex()
    {
        return $this->belongsTo(QuotationMarketingExport::class, 'quotation_m_ex_id','id');
    }

    public function items()
    {
        return $this->hasMany(ItemGroupQuotationMEx::class, 'group_quotation_m_ex_id', 'id');
    }
}
