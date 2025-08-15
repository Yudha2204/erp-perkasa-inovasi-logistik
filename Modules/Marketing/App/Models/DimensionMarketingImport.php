<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\DimensionMarketingImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DimensionMarketingImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'dimension_marketing_import';
    protected $guarded = [];

    public function marketing_import()
    {
        return $this->belongsTo(MarketingImport::class, 'marketing_import_id','id');
    }
}
