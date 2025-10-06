<?php

namespace Modules\GeneralLedger\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;

class GeneralJournalHead extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'general_journal_heads';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(GeneralJournalDetail::class, 'head_id', 'id');
    }

    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 9)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', $this->currency_id)
                    ->get();
        return $jurnal;
    }
}
