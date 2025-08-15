<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\ActivityOperationImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ActivityOperationImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'activity_operation_import';
    protected $guarded = [];

    public function operation_import()
    {
        return $this->belongsTo(OperationImport::class, 'operation_import_id','id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentActivityOpIm::class, 'activity_operation_import_id', 'id');
    }
}
