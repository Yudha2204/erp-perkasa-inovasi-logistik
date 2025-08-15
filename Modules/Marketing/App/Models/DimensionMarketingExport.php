<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\DimensionMarketingExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DimensionMarketingExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'dimension_marketing_export';
    protected $guarded = [];

    public function marketing_export()
    {
        return $this->belongsTo(MarketingExport::class, 'marketing_export_id','id');
    }
}
