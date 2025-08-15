<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\DocumentArrivalOpImFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DocumentArrivalOpIm extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'document_arrival_op_im';
    protected $guarded = [];

    public function operation_import()
    {
        return $this->belongsTo(OperationImport::class, 'operation_import_id','id');
    }
}
