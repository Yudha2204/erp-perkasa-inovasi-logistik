<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\DocumentMarketingImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentMarketingImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'document_marketing_import';
    protected $guarded = [];

    public function marketing_import()
    {
        return $this->belongsTo(MarketingImport::class, 'marketing_import_id','id');
    }
}
