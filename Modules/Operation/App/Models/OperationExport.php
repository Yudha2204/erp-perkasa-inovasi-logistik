<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\OperationExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Marketing\App\Models\MarketingExport;
use Spatie\Permission\Traits\HasRoles;

class OperationExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'operation_export';
    protected $guarded = [];

    public function marketing()
    {
        return $this->belongsTo(MarketingExport::class, 'marketing_export_id','id');
    }

    public function vendors()
    {
        return $this->hasMany(VendorOperationExport::class, 'operation_export_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentArrivalOpEx::class, 'operation_export_id', 'id');
    }

    public function activity()
    {
        return $this->hasOne(ActivityOperationExport::class, 'operation_export_id', 'id');
    }

    public function progress()
    {
        return $this->hasMany(ProgressOperationExport::class, 'operation_export_id', 'id');
    }
}
