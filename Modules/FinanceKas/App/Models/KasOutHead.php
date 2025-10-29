<?php


namespace Modules\FinanceKas\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Spatie\Permission\Traits\HasRoles;

class KasOutHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'kas_out_head';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(KasOutDetail::class, 'head_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }

    public function getTransactionAttribute()
    {
        $transaction_template = NoTransactionsKasOut::withTrashed()->find($this->transaction_id)->template;
        return "$transaction_template$this->number";
    }

    public function getTotalAttribute()
    {
        $details = KasOutDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($details as $d) {
            $total += $d->total;
        }
        return $total;
    }

    public function getJobOrderAttribute()
    {
        $job = $this->job_order_id;
        if($job) {
            if($this->source === "export") {
                return MarketingExport::find($job);
            } else if($this->source === "import") {
                return MarketingImport::find($job);
            }
        }
        return null;
    }

    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 5)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', $this->currency_id)
                    ->get();
        return $jurnal;
    }

    public function getJurnalIDRAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 5)
                    ->where('transaction_id', $this->id)
                    ->where('currency_id', 1)
                    ->get();
        return $jurnal;
    }

    protected $appends = ['transaction', 'total', 'job_order', 'jurnalIDR'];
}
