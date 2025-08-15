<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\QuotationMarketingExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Spatie\Permission\Traits\HasRoles;

class QuotationMarketingExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'quotation_marketing_export';
    protected $guarded = [];

    public function marketing_export()
    {
        return $this->belongsTo(MarketingExport::class, 'marketing_export_id','id');
    }

    public function groups()
    {
        return $this->hasMany(GroupQuotationMEx::class, 'quotation_m_ex_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id','id');
    }
}
