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

    public function getDebitKreditAll($startDate, $endDate)
    {
        $balance = BalanceAccount::where('master_account_id', $this->id)
                    ->whereNot('transaction_type_id', 1)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();
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

    public function getDebitKreditSaldoAwal()
    {
        $balance = BalanceAccount::where('master_account_id', $this->id)->where('transaction_type_id', 1)->get();
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

    public function getNetMutation($startDate, $endDate)
    {
        $data = $this->getDebitKreditAll($startDate, $endDate);
        $saldoAwal = $this->getDebitKreditSaldoAwal();

        $debitNet = $data['debit'] - $saldoAwal['debit'];
        $kreditNet = $data['kredit'] - $saldoAwal['kredit'];

        $bigger = $kreditNet - $debitNet;

        return $bigger;
    }
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['currency_id'])) {
            $query->where('master_currency_id', $filters['currency_id']);
        }

        if (!empty($filters['account_type_id'])) {
            $query->where('account_type_id', $filters['account_type_id']);
        }

        // tambahkan filter lain sesuai kebutuhan
        return $query;
    }
}
