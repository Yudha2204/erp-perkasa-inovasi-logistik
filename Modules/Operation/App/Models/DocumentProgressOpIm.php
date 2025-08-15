<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\DocumentProgressOpImFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentProgressOpIm extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'document_progress_op_im';
    protected $guarded = [];

    public function progress_operation()
    {
        return $this->belongsTo(ProgressOperationImport::class, 'progress_operation_import_id','id');
    }
}
