<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\ProgressOperationExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProgressOperationExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'progress_operation_export';
    protected $guarded = [];

    public function operation_export()
    {
        return $this->belongsTo(OperationExport::class, 'operation_export_id','id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentProgressOpEx::class, 'progress_operation_export_id', 'id');
    }
}
