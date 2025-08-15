<?php

namespace Modules\FinancePiutang\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Modules\Marketing\App\Models\ItemGroupQuotationMEx;
use Modules\Marketing\App\Models\ItemGroupQuotationMIm;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Modules\Marketing\App\Models\QuotationMarketingExport;
use Modules\Marketing\App\Models\QuotationMarketingImport;

class FinancePiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('financepiutang::index');        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financepiutang::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('financepiutang::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('financepiutang::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function getJobOrder(Request $request)
    {
        $contact_id = $request->input('contact');
        $currency_id = $request->input('currency');
        if(!$currency_id) {
            return response()->json([
                'message' => 'Error'
            ]);
        }

        $export = MarketingExport::whereHas('quotation', function ($query) use ($currency_id) {
                    $query->where('currency_id', $currency_id);
                })
                ->with('quotation')
                ->where('status', 2)
                ->where('contact_id', $contact_id)
                ->get();
        $import = MarketingImport::whereHas('quotation', function ($query) use ($currency_id) {
                    $query->where('currency_id', $currency_id);
                })
                ->with('quotation')
                ->where('status', 2)
                ->where('contact_id', $contact_id)
                ->get();
        $marketing = $export->concat($import);

        return response()->json([
            'message' => 'Success',
            'data'    => $marketing
        ]); 
    }

    public function getMarketing(Request $request)
    {
        $selected_option = $request->input('id');
        list($id, $source) = explode(':', $selected_option);

        if ($source == 'export') {
            $quotation = QuotationMarketingExport::where('marketing_export_id', $id)->firstOrFail();
            $quotationId = $quotation->id;
            $groupData = ItemGroupQuotationMEx::whereHas('group_quotation_m_ex', function($query) use ($quotationId) {
                $query->where('quotation_m_ex_id', $quotationId);
            })->get();
            $marketing = MarketingExport::with('quotation')->find($id);
        } else {
            $quotation = QuotationMarketingImport::where('marketing_import_id', $id)->firstOrFail();
            $quotationId = $quotation->id;
            $groupData = ItemGroupQuotationMIm::whereHas('group_quotation_m_im', function($query) use ($quotationId) {
                $query->where('quotation_m_im_id', $quotationId);
            })->get();
            $marketing = MarketingImport::with('quotation')->find($id);
        }

        return response()->json([
            'message' => "Success",
            'data'    => [
                'marketing' => $marketing,
                'item' => $groupData
            ]
        ]); 
    }

    public function getSalesOrder(Request $request)
    {
        $id = $request->input('id');
        $salesOrder = SalesOrderHead::with(['details'])->find($id);

        return response()->json([
            'message' => 'Success',
            'data'    => [
                "sales"  => $salesOrder,
                "marketing" => $salesOrder->marketing
            ]
        ]);
    }

    public function getInvoice(Request $request) {
        $id = $request->invoice;
        $invoice = InvoiceHead::find($id);

        return response()->json([
            'message' => "Success",
            'data'    => $invoice
        ]);
    }
}
