<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\Database\factories\BankAccountFactory;
use Spatie\Permission\Traits\HasRoles;

class BankAccount extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'master_bank_currency';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     */
    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }
}
