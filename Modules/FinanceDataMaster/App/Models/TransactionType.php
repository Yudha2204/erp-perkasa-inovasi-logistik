<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\TransactionTypeFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class TransactionType extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'transaction_type';
    protected $guarded = [];

    public function balance_accounts()
    {
        return $this->hasMany(BalanceAccount::class, 'transaction_type_id', 'id');
    }
}
