<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\VendorOperationImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinancePayments\App\Models\OrderHead;
use Spatie\Permission\Traits\HasRoles;

class VendorOperationImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'vendor_operation_import';
    protected $guarded = [];

    public function operation_import()
    {
        return $this->belongsTo(OperationImport::class, 'operation_import_id','id');
    }

    public function getVendorFinance()
    {
        if($this->vendor) {
            return OrderHead::find($this->vendor);
        }
    }
}
