<?php

namespace Modules\Marketing\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Marketing\Database\factories\MarketingImportFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceKas\App\Models\KasOutHead;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\Operation\App\Models\OperationImport;
use Spatie\Permission\Traits\HasRoles;

class MarketingImport extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'marketing_import';
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id','id');
    }

    public function dimensions()
    {
        return $this->hasMany(DimensionMarketingImport::class, 'marketing_import_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentMarketingImport::class, 'marketing_import_id', 'id');
    }

    public function quotation()
    {
        return $this->hasOne(QuotationMarketingImport::class, 'marketing_import_id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(OperationImport::class, 'marketing_import_id', 'id');
    }
    
    public function getDataCalculate()
    {
        $sellingInvoice = 0;
        $isInvoice = false;
        if($this->quotation) {
            $invoice = InvoiceHead::whereHas('sales', function ($query) {
                $query->where('marketing_id', $this->id)
                      ->where('source', 'import');
            })->get();
            foreach($invoice as $i) {
                $isInvoice = true;
                $sellingInvoice += $i->total;
            }
        }

        $cost = 0;
        $profit = 0;
        $selling = 0;
        if($this->quotation) {
            $kas_out = KasOutHead::where('job_order_id', $this->id)->where('source', 'import')->get();
            foreach($kas_out as $ko) {
                $pembagi = 1;
                if($this->quotation->currency_id !== $ko->currency_id) {
                    $exchange = ExchangeRate::where('date', $ko->date_kas_out)
                            ->where('from_currency_id', $this->quotation->currency_id)
                            ->where('to_currency_id', $ko->currency_id)
                            ->get()
                            ->first();
                    if(!$exchange) {
                        $exchange = ExchangeRate::where('date', $ko->date_kas_out)
                                ->where('to_currency_id', $this->quotation->currency_id)
                                ->where('from_currency_id', $ko->currency_id)
                                ->get()
                                ->first();
                    }
                    
                    if($exchange) {
                        if($exchange->from_currency_id === $ko->currency_id) {
                            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
                        } else {
                            $pembagi = $exchange->from_nominal/$exchange->to_nominal;
                        }
                    } else {
                        $pembagi = 0;
                    }
                }

                $cost_ko = ($ko->total)*$pembagi; 
                $selling = $isInvoice === false ? $this->quotation->sales_value : $sellingInvoice;
                $profit_ko = $pembagi === 0 ? 0 : $selling - $cost_ko;
                $cost += $cost_ko;
                $profit += $profit_ko;
            }

            $operationsId = $this->operations->pluck('id');
            $purchase_order = OrderHead::whereIn('operation_id', $operationsId)
                    ->where('source', 'import')->get();

            foreach($purchase_order as $po) {
                $pembagi = 1;
                if($this->quotation->currency_id !== $po->currency_id) {
                    $exchange = ExchangeRate::where('date', $po->date_order)
                            ->where('from_currency_id', $this->quotation->currency_id)
                            ->where('to_currency_id', $po->currency_id)
                            ->get()
                            ->first();
                    if(!$exchange) {
                        $exchange = ExchangeRate::where('date', $po->date_order)
                                ->where('to_currency_id', $this->quotation->currency_id)
                                ->where('from_currency_id', $po->currency_id)
                                ->get()
                                ->first();
                    }
                    
                    if($exchange) {
                        if($exchange->from_currency_id === $po->currency_id) {
                            $pembagi = $exchange->to_nominal/$exchange->from_nominal;
                        } else {
                            $pembagi = $exchange->from_nominal/$exchange->to_nominal;
                        }
                    } else {
                        $pembagi = 0;
                    }
                }

                $cost_po = ($po->total)*$pembagi; 
                $selling = $isInvoice === false ? $this->quotation->sales_value : $sellingInvoice;
                $profit_po = $pembagi === 0 ? 0 : $selling - $cost_po;
                $cost += $cost_po;
                $profit += $profit_po;
            }
        }

        return [
            "cost" => $cost,
            "profit" => $profit,
            "selling" => $selling,
            "isInvoice" => $isInvoice
        ];
    }
}
