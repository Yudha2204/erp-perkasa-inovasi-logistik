<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\MasterAccountFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterAccount extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'master_account';
    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'master_currency_id', 'id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id', 'id');
    }

    public function balance_accounts()
    {
        return $this->hasMany(BalanceAccount::class, 'master_account_id', 'id');
    }

    public function getDebitKreditAll($startDate, $endDate, $currencyId = null)
    {
        $balance = BalanceAccount::where('master_account_id', $this->id)
                    ->whereNot('transaction_type_id', 1)
                    ->whereBetween('date', [$startDate, $endDate]);
        
        if ($currencyId !== null) {
            $balance->where('currency_id', $currencyId);
        }

        $balance = $balance->get();
        
        $debit = 0;
        $kredit = 0;
        foreach($balance as $ba) {
            $debit += $ba->debit;
            $kredit += $ba->credit;
        }

        return [
            "debit" => $debit,
            "kredit" => $kredit
        ];
    }

    public function getDebitKreditSaldoAwal($currencyId = null)
    {
        // Always get beginning balance from balance_account_data with transaction_type_id = 1
        $balance = BalanceAccount::where('master_account_id', $this->id)
                    ->where('transaction_type_id', 1);
        
        if ($currencyId !== null) {
            $balance->where('currency_id', $currencyId);
        }
        
        $balance = $balance->get();
        
        $debit = 0;
        $kredit = 0;
        foreach($balance as $ba) {
            $debit += $ba->debit;
            $kredit += $ba->credit;
        }

        return [
            "debit" => $debit,
            "kredit" => $kredit
        ];
    }

    public function getNetMutation($startDate, $endDate, $currencyId = null)
    {
        $data = $this->getDebitKreditAll($startDate, $endDate, $currencyId);
        $saldoAwal = $this->getDebitKreditSaldoAwal($currencyId);

        // Calculate saldo akhir: Saldo Awal + Mutasi Debit - Mutasi Kredit
        // For debit accounts: positive result means debit balance
        // For credit accounts: negative result means credit balance
        $saldoAwalNet = $saldoAwal['debit'] - $saldoAwal['kredit'];
        $mutasiNet = $data['debit'] - $data['kredit'];
        $saldoAkhirNet = $saldoAwalNet + $mutasiNet;

        return $saldoAkhirNet;
    }
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['currency_id'])) {
            $query->where('master_currency_id', $filters['currency_id']);
        }

        if (!empty($filters['account_type_id'])) {
            // Handle both single value and array of values
            if (is_array($filters['account_type_id'])) {
                $query->whereIn('account_type_id', $filters['account_type_id']);
            } else {
                $query->where('account_type_id', $filters['account_type_id']);
            }
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // tambahkan filter lain sesuai kebutuhan
        return $query;
    }
}
