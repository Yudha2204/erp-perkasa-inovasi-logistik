<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\ProgressOperationImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProgressOperationImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'progress_operation_import';
    protected $guarded = [];

    public function operation_import()
    {
        return $this->belongsTo(OperationImport::class, 'operation_import_id','id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentProgressOpIm::class, 'progress_operation_import_id', 'id');
    }
}
