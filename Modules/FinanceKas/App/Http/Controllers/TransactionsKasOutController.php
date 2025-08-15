<?php

namespace Modules\FinanceKas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceKas\App\Models\NoTransactionsKasOut;

class TransactionsKasOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $head = $request->input('head-code');
        if(!$head) {
            return redirect()->back()->withErrors(['error' => 'Please input transaction head.']);
        }
        $year = date('Y');
        $tail = $request->input('tail-code');
        if(!$tail) {
            return redirect()->back()->withErrors(['error' => 'Please input transaction tail.']);
        }
        $start = $request->input('start-code');
        if(!$start || !is_numeric($start)) {
            return redirect()->back()->withErrors(['error' => 'Please input transaction start number.']);
        }
    
        $template = "$head/$year/$tail/";

        $exists = NoTransactionsKasOut::where('template', $template)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Template transaction already exists.']);
        }
    
        $data = [
            'start' => $start,
            'template' => $template,
        ];
    
        NoTransactionsKasOut::create($data);
        toast('Data Added Successfully!', 'success');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
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
        $transactions = NoTransactionsKasOut::findOrFail($id);
        $transactions->delete();
        return response()->json([
            "message" => "Success"
        ]);
    }
}
