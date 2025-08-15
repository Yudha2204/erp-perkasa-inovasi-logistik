<?php
namespace Modules\ReportFinance\App\Models;
use Illuminate\Database\Eloquent\Model;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePiutang\App\Models\InvoiceHead;
class Sao extends Model
{
    protected $table = 'sao';
    protected $fillable = [
        'invoice_id',
        'order_id',
        'vendor_id',
        'contact_id',
        'currency_id',
        'date',
        'account',
        'total',
        'already_paid',
        'remaining',
        'isPaid',
        "type",
    ];
    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(MasterCurrency::class, 'currency_id', 'id');
    }
    public function invoice()
    {
        return $this->belongsTo(InvoiceHead::class, 'invoice_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(OrderHead::class, 'order_id', 'id');
    }
    public function vendor()
    {
        return $this->belongsTo(MasterContact::class, 'vendor_id', 'id');
    }
}