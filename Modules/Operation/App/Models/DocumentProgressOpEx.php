<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\DocumentProgressOpExFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentProgressOpEx extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'document_progress_op_ex';
    protected $guarded = [];

    public function progress_operation()
    {
        return $this->belongsTo(ProgressOperationExport::class, 'progress_operation_export_id','id');
    }
}
