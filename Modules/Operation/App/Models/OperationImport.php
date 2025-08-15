<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\OperationImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Marketing\App\Models\MarketingImport;
use Spatie\Permission\Traits\HasRoles;

class OperationImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'operation_import';
    protected $guarded = [];

    public function marketing()
    {
        return $this->belongsTo(MarketingImport::class, 'marketing_import_id','id');
    }

    public function vendors()
    {
        return $this->hasMany(VendorOperationImport::class, 'operation_import_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentArrivalOpIm::class, 'operation_import_id', 'id');
    }

    public function activity()
    {
        return $this->hasOne(ActivityOperationImport::class, 'operation_import_id', 'id');
    }

    public function progress()
    {
        return $this->hasMany(ProgressOperationImport::class, 'operation_import_id', 'id');
    }
}
