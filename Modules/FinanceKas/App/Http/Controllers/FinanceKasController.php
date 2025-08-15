<?php

namespace Modules\FinanceKas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;

class FinanceKasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('financekas::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financekas::create');
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
        return view('financekas::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('financekas::edit');
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

        $export = MarketingExport::with('quotation')
                ->where('status', 2)
                ->where('contact_id', $contact_id)
                ->get();
        $import = MarketingImport::with('quotation')
                ->where('status', 2)
                ->where('contact_id', $contact_id)
                ->get();
        $marketing = $export->concat($import);

        return response()->json([
            'message' => 'Success',
            'data'    => $marketing
        ]); 
    }

    public function getJobOrderDetails(Request $request)
    {
        $job_order_id = $request->job_order_id;
        $job_order_source = $request->job_order_source;

        if($job_order_source === "import") {
            return MarketingImport::find($job_order_id);
        } else if($job_order_source === "export") {
            return MarketingExport::find($job_order_id);
        }
    }
}
