<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\ActivityOperationExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ActivityOperationExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'activity_operation_export';
    protected $guarded = [];

    public function operation_export()
    {
        return $this->belongsTo(OperationExport::class, 'operation_export_id','id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentActivityOpEx::class, 'activity_operation_export_id', 'id');
    }
}
