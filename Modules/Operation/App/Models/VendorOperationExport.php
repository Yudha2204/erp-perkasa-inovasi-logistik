<?php

namespace Modules\Operation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operation\Database\factories\VendorOperationExportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinancePayments\App\Models\OrderHead;
use Spatie\Permission\Traits\HasRoles;

class VendorOperationExport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'vendor_operation_export';
    protected $guarded = [];

    public function operation_export()
    {
        return $this->belongsTo(OperationExport::class, 'operation_export_id','id');
    }

    public function getVendorFinance()
    {
        if($this->vendor) {
            return OrderHead::find($this->vendor);
        }
    }
}
