<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\BalanceAccountFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceKas\App\Models\KasInHead;
use Modules\FinanceKas\App\Models\KasOutHead;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePayments\App\Models\PaymentHead;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\RecieveHead;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Modules\GeneralLedger\App\Models\GeneralJournalHead;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class BalanceAccount extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    // protected static function booted(): void
    // {
    //     // Secara anonim menambahkan Global Scope untuk pengurutan.
    //     // Scope ini otomatis diterapkan ke semua query.
    //     static::addGlobalScope('debit_priority', function (Builder $builder) {

    //         // Logika pengurutan: Prioritaskan Debit (nilai > 0)
    //         $builder->orderByDesc('debit')

    //                 // Kemudian, urutkan berdasarkan Kredit
    //                 ->orderByDesc('kredit');
    //     });
    // }

    public function setDebitAttribute(float $value): void
    {
        // Mengambil nilai absolut (abs) dari input dan menyimpannya.
        $this->attributes['debit'] = abs($value);
    }
    public function setCreditAttribute(float $value): void
    {
        // Mengambil nilai absolut (abs) dari input dan menyimpannya.
        $this->attributes['credit'] = abs($value);
    }

    protected $table = 'balance_account_data';
    protected $guarded = [];

    // protected $appends = ['jurnalIDR'];

    public function master_account()
    {
        return $this->belongsTo(MasterAccount::class, 'master_account_id', 'id');
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }


    public function getTransaction()
    {
        $transaksi = null;
        if($this->transaction_type_id === 2) {
            $transaksi = SalesOrderHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 3) {
            $transaksi = InvoiceHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 4) {
            $transaksi = RecieveHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 5) {
            $transaksi = KasOutHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 6) {
            $transaksi = KasInHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 7) {
            $transaksi = OrderHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 8) {
            $transaksi = PaymentHead::find($this->transaction_id);
        } else if($this->transaction_type_id === 9) {
            $transaksi = GeneralJournalHead::find($this->transaction_id);
        }

        return $transaksi;
    }

    // public function getJurnalIDRAttribute()
    // {
    //     $jurnal = BalanceAccount::where('transaction_type_id', $this->transaction_type_id)
    //                 ->where('transaction_id', $this->transaction_id)
    //                 ->where('currency_id', 1)
    //                 ->get();
    //     return $jurnal->toArray();
    // }

    private function getBaseCurrAmount(float $amount, $date, int $fromCurrencyId, int $toCurrencyId): float
    {
        // 1) Same-currency shortcut
        if ($fromCurrencyId === $toCurrencyId) {
            return $amount;
        }

        // 2) Find a rate for that date in either direction
        $rate = ExchangeRate::query()
            ->whereDate('date', $date)
            ->where(function ($q) use ($fromCurrencyId, $toCurrencyId) {
                $q->where(function ($q) use ($fromCurrencyId, $toCurrencyId) {
                    $q->where('from_currency_id', $fromCurrencyId)
                    ->where('to_currency_id', $toCurrencyId);
                })->orWhere(function ($q) use ($fromCurrencyId, $toCurrencyId) {
                    $q->where('from_currency_id', $toCurrencyId)
                    ->where('to_currency_id', $fromCurrencyId);
                });
            })
            // If duplicates can exist for a date, prefer the latest updated/inserted:
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if (!$rate) {
            throw new \Exception('Failed to convert amount: exchange rate not found for the given date/currencies.');
        }

        // 3) Validate denominators
        if (empty($rate->from_nominal) || empty($rate->to_nominal)) {
            throw new \Exception('Invalid exchange rate record: nominal values cannot be zero or null.');
        }

        // 4) Compute factor based on orientation of the stored pair
        // Meaning: from_nominal units of "from" == to_nominal units of "to"
        $isDirect = ((int)$rate->from_currency_id === $fromCurrencyId)
                && ((int)$rate->to_currency_id === $toCurrencyId);

        $factor = $isDirect
            ? ($rate->to_nominal / $rate->from_nominal)   // from -> to
            : ($rate->from_nominal / $rate->to_nominal);  // to -> from (invert)

        // 5) Multiply (no abs!)
        return $amount * $factor;
    }

    protected static function booted()
    {
        static::creating(function ($balanceAccount) {
            // Check if transaction date is before start_entry_period
            if (\App\Models\Setup::isDateBeforeStartEntryPeriod($balanceAccount->date) && $balanceAccount->transaction_type_id != 1) {
                $startEntryPeriod = \App\Models\Setup::getStartEntryPeriod();
                throw new \Exception("Transaction date cannot be before the start entry period ({$startEntryPeriod->format('d/m/Y')})");
            }

            //check if currency is not idr create another balance account for idr
            $baseCurrency = MasterCurrency::where('initial', 'IDR')->first();

            if (!$balanceAccount->currency_id)
                throw new \Exception('Currency Cant Null');


            if ($balanceAccount->currency_id != $baseCurrency->id) {
                // clone balance account
                // Create a clone for IDR (currency_id = 2)
                $debitAmt = $balanceAccount->getBaseCurrAmount($balanceAccount->debit, $balanceAccount->date, $balanceAccount->currency_id, $baseCurrency->id);
                $creditAmt = $balanceAccount->getBaseCurrAmount($balanceAccount->credit, $balanceAccount->date, $balanceAccount->currency_id, $baseCurrency->id);

                // Only create if not already for IDR
                static::create([
                    'transaction_id' => $balanceAccount->transaction_id,
                    'master_account_id' => $balanceAccount->master_account_id,
                    'transaction_type_id' => $balanceAccount->transaction_type_id,
                    'date' => $balanceAccount->date,
                    'debit' => $debitAmt > 0 ? $debitAmt : 0,
                    'credit' => $creditAmt > 0 ? $creditAmt : 0,
                    'currency_id' => $baseCurrency->id, // IDR
                ]);
            }
        });
            static::addGlobalScope('debit_priority', function (Builder $builder) {

            // Logika pengurutan: Prioritaskan Debit (nilai > 0)
            $builder->orderByDesc('debit')

                    // Kemudian, urutkan berdasarkan Kredit
                    ->orderByDesc('credit');
        });
    }
}
