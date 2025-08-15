<?php

namespace Modules\ExchangeRate\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Spatie\Permission\Traits\HasRoles;

class ExchangeRate extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'master_exchange';
    protected $guarded = [];

    public function from_currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'from_currency_id', 'id');
    }

    public function to_currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'to_currency_id', 'id');
    }
}
