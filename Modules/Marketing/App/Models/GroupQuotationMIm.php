<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\GroupQuotationMImFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class GroupQuotationMIm extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'group_quotation_m_im';
    protected $guarded = [];

    public function quotation_m_im()
    {
        return $this->belongsTo(QuotationMarketingImport::class, 'quotation_m_im_id','id');
    }

    public function items()
    {
        return $this->hasMany(ItemGroupQuotationMIm::class, 'group_quotation_m_im_id', 'id');
    }
}
