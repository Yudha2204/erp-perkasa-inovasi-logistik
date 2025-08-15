<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\ItemGroupQuotationMImFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ItemGroupQuotationMIm extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'item_group_quotation_m_im';
    protected $guarded = [];

    public function group_quotation_m_im()
    {
        return $this->belongsTo(GroupQuotationMIm::class, 'group_quotation_m_im_id','id');
    }
}
