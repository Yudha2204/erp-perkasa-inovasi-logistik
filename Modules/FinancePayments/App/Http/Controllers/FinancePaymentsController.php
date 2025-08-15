<?php

namespace Modules\FinancePayments\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;

class FinancePaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('financepayments::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financepayments::create');
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
        return view('financepayments::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('financepayments::edit');
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
        $customer = $request->customer_id;
        $export = OperationExport::with('marketing')
            ->whereHas('marketing', function ($query) use ($customer) {
                $query->where('contact_id', $customer);
            })->get();
        $import = OperationImport::with('marketing')
            ->whereHas('marketing', function ($query) use ($customer) {
                $query->where('contact_id', $customer);
            })->get();
        $job_order = $export->concat($import);
        return response()->json([
            "message" => "Success",
            "data" => $job_order
        ]);
    }

    public function getJobOrderDetails(Request $request)
    {
        $selected = $request->id;
        list($job_order_id, $job_order_source) = explode(":", $selected);
        
        if($job_order_source === "import") {
            $job_order = OperationImport::with(['marketing','vendors'])->find($job_order_id);
        } else if($job_order_source === "export") {
            $job_order = OperationExport::with(['marketing','vendors'])->find($job_order_id);
        }

        return response()->json([
            "message" => "Success",
            "data" => $job_order
        ]);
    }
    
    public function getOrder(Request $request)
    {
        $vendor = $request->vendor;
        $customer = $request->customer;
        $currency = $request->currency;
        $job_order = $request->job_order;

        $purchaseOrder = [];
        if($currency && $vendor) {
            if($job_order && $job_order !== "null") {
                $exp_jo = explode(":", $job_order);
                $job_order_id = $exp_jo[0];
                $job_order_source = $exp_jo[1];
                $purchaseOrder = OrderHead::where('customer_id', $customer)
                            ->where('vendor_id', $vendor)
                            ->where('currency_id', $currency)
                            ->where('operation_id', $job_order_id)
                            ->where('source', $job_order_source)
                            ->where('status','!=','paid')
                            ->get();
            } else {
                $purchaseOrder = OrderHead::where('customer_id', $customer)
                    ->where('vendor_id', $vendor)
                    ->where('currency_id', $currency)
                    ->where('operation_id', null)
                    ->where('status','!=','paid')
                    ->get();
            }
        }

        return response()->json([
            'message' => 'Success',
            'data' => $purchaseOrder
        ]);
    }

    public function getOrderDetails(Request $request)
    {
        $order = $request->order;
        $order = OrderHead::find($order);

        return response()->json([
            "message" => "Success",
            "data" => $order
        ]);
    }
}