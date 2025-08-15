<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\DocumentActivityOpExFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentActivityOpEx extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'document_activity_op_ex';
    protected $guarded = [];

    public function activity_operation_export()
    {
        return $this->belongsTo(ActivityOperationExport::class, 'activity_operation_export_id','id');
    }
}
