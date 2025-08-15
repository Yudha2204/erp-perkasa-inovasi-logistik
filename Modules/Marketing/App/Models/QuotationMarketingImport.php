<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\QuotationMarketingImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Spatie\Permission\Traits\HasRoles;

class QuotationMarketingImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'quotation_marketing_import';
    protected $guarded = [];

    public function marketing_import()
    {
        return $this->belongsTo(MarketingImport::class, 'marketing_import_id','id');
    }

    public function groups()
    {
        return $this->hasMany(GroupQuotationMIm::class, 'quotation_m_im_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id','id');
    }
}
