<?php

namespace Modules\FinancePiutang\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Illuminate\Support\Facades\Validator;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\SalesOrderDetail;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-sales_order@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-sales_order@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-sales_order@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-sales_order@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales_order = SalesOrderHead::all();
        return view('financepiutang::sales-order.index', compact('sales_order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();

        $current_year = Carbon::now()->year;
        $sales = SalesOrderHead::whereYear('date', $current_year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($sales) {
            $latest_number = $sales->number + 1;
        }

        return view('financepiutang::sales-order.create', compact('contact', 'terms', 'currencies', 'latest_number'));
    }

    public function getTransaction(Request $request)
    {
        $date = $request->get('date');
        $sales = SalesOrderHead::whereYear('date', Carbon::parse($date)->year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($sales) {
            $latest_number = $sales->number + 1;
        }

        return response()->json([
            'message' => "Success",
            'data' => $latest_number
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required',
            'no_transaction'    => 'required',
            'date_sales' => 'required',
            'currency_id' => 'required',
        ], [
            "customer_id.required" => "The customer field is required.",
            "no_transaction.required" => "The transaction number is required.",
            "date_sales.required" => "The date is required.",
            "currency_id.required" => "The currency field is required."
        ]);

        if ($validator->fails()) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors($validator)->withInput();
        }

        $contact_id = $request->input('customer_id');
        $no_transactions = $request->input('no_transaction');
        $date = $request->input('date_sales');
        $currency_id = $request->input('currency_id');
        $description = $request->input('des_head_sales');
        $choose = $request->input('choose_job_order');
        $additional_cost = $request->input('additional_cost');
        $discount_type = $request->input('discount_type');
        $discount_nominal = $request->input('discount');

        $exp_transaction = explode("-", $no_transactions);
        $number = $exp_transaction[3];

        $marketing_id = null;
        $marketing_source = null;
        if($choose === "1") {
            $marketing = $request->input('no_referensi');
            if($marketing) {
                $exp_marketing = explode(":", $marketing);
                if(sizeof($exp_marketing) !== 2) {
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi'])->withInput();
                }
                $marketing_id = $exp_marketing[0];
                $marketing_source = $exp_marketing[1];
            }
        }

        SalesOrderHead::create([
            'contact_id' => $contact_id,
            'number' => $number,
            'date' => $date,
            'currency_id' => $currency_id,
            'description' => $description,
            'marketing_id' => $marketing_id,
            'source' => $marketing_source,
            'additional_cost' => $this->numberToDatabase($additional_cost),
            'discount_type' => $discount_type,
            'discount_nominal' => $this->numberToDatabase($discount_nominal)
        ]);

        $newestSalesOrder = SalesOrderHead::latest()->first();
        $head_id = $newestSalesOrder->id;

        $formData = json_decode($request->input('form_data'), true);
        foreach ($formData as $data) {
            $des_detail = $data['des_detail'];
            $remark_detail = $data['remark_detail'];
            $qty_detail = $this->numberToDatabase($data['qty_detail']);
            $uom_detail = $data['uom_detail'];
            $price_detail = $this->numberToDatabase($data['price_detail']);
            $disc_detail = $this->numberToDatabase($data['disc_detail']);
            $disc_type_detail = $data['disc_type_detail'];

            SalesOrderDetail::create([
                'head_id' => $head_id,
                'description' => $des_detail,
                'quantity' => $qty_detail,
                'uom' => $uom_detail,
                'price' => $price_detail,
                'remark' => $remark_detail,
                'discount_type' => $disc_type_detail,
                'discount_nominal' => $disc_detail,
            ]);
        }
        return redirect()->route('finance.piutang.sales-order.index')->with('success', 'create successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $dataSalesOrder = SalesOrderHead::with('currency','details','contact')->find($id);
        return view('financepiutang::sales-order.read', compact('dataSalesOrder'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = InvoiceHead::where('sales_id', $id)
                    ->first();
        // if($invoice) {
        //     toast('Failed to Update Data!', 'error');
        //     return redirect()->route('finance.piutang.sales-order.index')->withErrors(["error" => "There is an invoice link to this sales order"]);
        // }

        $dataSalesOrder = SalesOrderHead::with('currency','details','contact')->find($id);
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();

        $currency_id = $dataSalesOrder->currency_id;
        $contact_id = $dataSalesOrder->contact_id;
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

        return view('financepiutang::sales-order.update', compact('dataSalesOrder', 'contact', 'terms', 'currencies', 'marketing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required',
            'no_transaction'    => 'required',
            'date_sales' => 'required',
            'currency_id' => 'required',
        ], [
            "customer_id.required" => "The customer field is required.",
            "no_transaction.required" => "The transaction number is required.",
            "date_sales.required" => "The date is required.",
            "currency_id.required" => "The currency field is required."
        ]);

        if ($validator->fails()) {
            toast('Failed to Update Data!','error');
            return redirect()->back()
                        ->withErrors($validator)->withInput();
        }

        $contact_id = $request->input('customer_id');
        $no_transactions = $request->input('no_transaction');
        $date = $request->input('date_sales');
        $currency_id = $request->input('currency_id');
        $description = $request->input('des_head_sales');
        $choose = $request->input('choose_job_order');
        $additional_cost = $request->input('additional_cost');
        $discount_type = $request->input('discount_type');
        $discount_nominal = $request->input('discount');

        $exp_transaction = explode("-", $no_transactions);
        $number = $exp_transaction[3];

        $marketing_id = null;
        $marketing_source = null;
        if($choose === "1") {
            $marketing = $request->input('no_referensi');
            if($marketing) {
                $exp_marketing = explode(":", $marketing);
                if(sizeof($exp_marketing) !== 2) {
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi'])->withInput();
                }
                $marketing_id = $exp_marketing[0];
                $marketing_source = $exp_marketing[1];
            }
        }

        $salesOrder = SalesOrderHead::findOrFail($id);

        $salesOrder->update([
            'contact_id' => $contact_id,
            'number' => $number,
            'date' => $date,
            'currency_id' => $currency_id,
            'description' => $description,
            'marketing_id' => $marketing_id,
            'source' => $marketing_source,
            'additional_cost' => $this->numberToDatabase($additional_cost),
            'discount_type' => $discount_type,
            'discount_nominal' => $this->numberToDatabase($discount_nominal)
        ]);

        $head_id = $salesOrder->id;

        $formData = json_decode($request->input('form_data'), true);
        foreach ($formData as $data) {
            $des_detail = $data['des_detail'];
            $remark_detail = $data['remark_detail'];
            $qty_detail = $this->numberToDatabase($data['qty_detail']);
            $uom_detail = $data['uom_detail'];
            $price_detail = $this->numberToDatabase($data['price_detail']);
            $disc_detail = $this->numberToDatabase($data['disc_detail']);
            $disc_type_detail = $data['disc_type_detail'];
            $operator = $data['operator'];
            $exp_operator = explode(":", $operator);

            if($exp_operator[1] === "create") {
                SalesOrderDetail::create([
                    'head_id' => $head_id,
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'remark' => $remark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $disc_detail,
                ]);
            } else if($exp_operator[1] === "update") {
                $salesOrderDetail = SalesOrderDetail::find($exp_operator[0]);
                $salesOrderDetail->update([
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'remark' => $remark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $disc_detail,
                ]);
            } else if($exp_operator[1] === "delete") {
                $salesOrderDetail = SalesOrderDetail::find($exp_operator[0]);
                if($salesOrderDetail) {
                    $salesOrderDetail->delete();
                }
            }
        }

        return redirect()->route('finance.piutang.sales-order.index')->with('success', 'create successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = InvoiceHead::where('sales_id', $id)
                    ->first();
        if($invoice) {
            toast('Failed to Delete Data!', 'error');
            return redirect()->route('finance.piutang.sales-order.index')->withErrors(["error" => "There is an invoice link to this sales order"]);
        }

        SalesOrderDetail::where('head_id', $id)->delete();
        SalesOrderHead::findOrFail($id)->delete();
        
        toast('Data Deleted Successfully!', 'success');
        return redirect()->back()->with('success', 'delete successfully!');
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
