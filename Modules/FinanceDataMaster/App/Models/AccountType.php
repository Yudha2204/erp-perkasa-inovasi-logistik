<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\AccountTypeFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class AccountType extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'account_type';
    protected $guarded = [];

    public function master_accounts()
    {
        return $this->hasMany(MasterAccount::class, 'account_type_id', 'id');
    }

    public function classification()
    {
        return $this->belongsTo(ClassificationAccountType::class, 'classification_id', 'id');
    }

    public function getCashFlowNameAttribute()
    {
        if($this->cash_flow === 1) {
            return "Operation";
        } else if($this->cash_flow === 2) {
            return "Investing";
        } else if($this->cash_flow === 3) {
            return "Financing";
        }

        return "Undefined";
    }

    protected $appends = ['cash_flow_name'];
}
