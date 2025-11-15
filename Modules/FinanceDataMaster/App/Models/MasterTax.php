<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\MasterTaxFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterTax extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'master_tax';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }

    public function salesAccount()
    {
        return $this->belongsTo(MasterAccount::class, 'sales_account_id', 'id');
    }
}
