<?php

namespace Modules\FinancePiutang\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinancePiutang\Database\factories\SalesOrderHeadFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\Notification\App\Models\NotificationCustom;
use Modules\ReportFinance\App\Models\Sao;
use Spatie\Permission\Traits\HasRoles;

class InvoiceHead extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'invoice_head';
    protected $guarded = [];

    public function sao()
    {
        return $this->hasOne(Sao::class, 'invoice_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'head_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }

    public function term()
    {
        return $this->belongsTo(MasterTermOfPayment::class, 'term_payment', 'id');
    }

    public function sales()
    {
        return $this->belongsTo(SalesOrderHead::class, 'sales_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }

    public function getTransactionAttribute()
    {
        $date = $this->date_invoice;
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $number = sprintf('%04d', $this->number);

        return sprintf("INV-PIL%s-%02d-%04d", $year, $month, $number);
    }

    public function getTotalAttribute()
    {   
        $discount = $this->discount_nominal;
        $detail = InvoiceDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            return $total-(($discount/100)*$total);
        }
        return $total-$discount;
    }

    public function getDiscountAttribute()
    {
        $discount = $this->discount_nominal;
        $detail = InvoiceDetail::where('head_id', $this->id)->get();
        $total = 0;
        foreach($detail as $d) {
            $total += $d->total;
        }
        $total += $this->additional_cost;

        if($this->discount_type === "persen") {
            return ($discount/100)*$total;
        }
        return $discount;
    }

    public function getJurnalAttribute()
    {
        $jurnal = BalanceAccount::where('transaction_type_id', 3)
                    ->where('transaction_id', $this->id)
                    ->get();
        return $jurnal;
    }

    public function getDueDateAttribute()
    {
        $term = MasterTermOfPayment::find($this->term_payment)->pay_days;
        $dueDate = Carbon::parse($this->date_invoice)->addDays($term);
        return $dueDate->format('Y-m-d');
    }

    public function getDpAttribute()
    {
        $dp = 0;
        $detail = InvoiceDetail::where('head_id', $this->id)->get();
        foreach($detail as $d) {
            if($d->dp) {
                $dp += $d->dp;
            }
        }
        return $dp;
    }

    public function getDpReceiveAttribute()
    {
        $dpFromReceive = 0;
        $dpReceive = RecieveDetail::where('invoice_id', $this->id)
                        ->whereNotNull('dp_nominal')
                        ->get();
        foreach($dpReceive as $dp) {
            $dpFromReceive += $dp->dp;
        }
        return $dpFromReceive;
    }

    public function updateStatus()
    {
        $dueDate = Carbon::parse($this->due_date);
        $currentDate = Carbon::today();

        if($this->status !== "paid") {
            if($currentDate->equalTo($dueDate)) {
                $this->status = 'due date';

                $notification = NotificationCustom::where('remark', $this->transaction)->first();
                if(!$notification) {
                    NotificationCustom::create([
                        "group_name" => "finance",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "type" => "info-due-date",
                        "remark" => $this->transaction,
                        "content" => "Pemberitahuan: Tagihan Anda $this->transaction jatuh tempo/Due pada tanggal $this->due_date. Mohon pastikan pembayaran dilakukan tepat waktu untuk menghindari biaya keterlambatan."
                    ]);
                } else {
                    $notification->update([
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "type" => "info-due-date",
                        "content" => "Pemberitahuan: Tagihan Anda $this->transaction jatuh tempo/Due pada tanggal $this->due_date. Mohon pastikan pembayaran dilakukan tepat waktu untuk menghindari biaya keterlambatan."
                    ]);
                }
            } else if($currentDate->greaterThan($dueDate)) {
                $this->status = 'over due';

                $notification = NotificationCustom::where('remark', $this->transaction)->first();
                if(!$notification) {
                    NotificationCustom::create([
                        "group_name" => "finance",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "type" => "info-over-due",
                        "remark" => $this->transaction,
                        "content" => "Pemberitahuan: Tagihan Anda $this->transaction telah telat/Over Due dibayar. Mohon segera lakukan pembayaran untuk menghindari denda keterlambatan."
                    ]);
                } else {
                    $notification->update([
                        "type" => "info-over-due",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "content" => "Pemberitahuan: Tagihan Anda $this->transaction telah telat/Over Due dibayar. Mohon segera lakukan pembayaran untuk menghindari denda keterlambatan."
                    ]);
                }
            } else {
                $notification = NotificationCustom::where('remark', $this->transaction)->first();
                if($notification) {
                    $notification->delete();
                }
            }
        }

        $this->save();
    }

    protected $appends = ['transaction', 'total', 'discount', 'jurnal', 'due_date', 'dp', 'dp_receive'];
} 
