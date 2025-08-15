<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\DocumentActivityOpImFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentActivityOpIm extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'document_activity_op_im';
    protected $guarded = [];

    public function activity_operation_import()
    {
        return $this->belongsTo(ActivityOperationImport::class, 'activity_operation_import_id','id');
    }
}
