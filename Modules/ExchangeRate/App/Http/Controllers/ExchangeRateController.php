<?php

namespace Modules\ExchangeRate\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Modules\FinancePayments\App\Models\PaymentDetail;
use Modules\FinancePiutang\App\Models\RecieveDetail;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private function indexByDate($date) {
        return ExchangeRate::whereDate('date', $date)->get();
    }

    public function index(Request $request)
    {
        $date = $request->get('date');
        if($date) {
            $exchangeRate = $this->indexByDate($date);
        } else {
            $date = Carbon::now()->format('Y-m-d');
            $exchangeRate = $this->indexByDate($date);
        }
        $currencies = MasterCurrency::all();
        return view('exchangerate::index', compact('exchangeRate','currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exchangerate::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = $request->input('date');
        $formData = json_decode($request->input('form_data'), true);
        foreach ($formData as $form) {
            $operation = $form['operator'];
            $from_currency = $form['from_currency'];
            $from_nominal = $form['from_nominal'];
            $to_currency = $form['to_currency'];
            $to_nominal = $form['to_nominal'];
            if(!($from_currency && $from_nominal && $to_currency && $to_nominal)) {
                toast('Failed to Add Data!','error');
                return redirect()->back()
                ->withErrors(["error" => "Please fill all data"]);
            }
            
            $from_nominal = $this->numberToDatabase($from_nominal);
            $to_nominal = $this->numberToDatabase($to_nominal);
            $data = [
                'date' => $date,
                'from_currency_id' => $from_currency,
                'from_nominal' => $from_nominal,
                'to_currency_id' => $to_currency,
                'to_nominal' => $to_nominal,
            ];

            $exp_operation = explode(":", $operation);

            if($exp_operation[0] === "create"){
                $exchangeExisting = ExchangeRate::whereDate('date', $date)
                                    ->where('from_currency_id', $from_currency)
                                    ->where('to_currency_id', $to_currency)
                                    ->get()
                                    ->first();
                if($exchangeExisting) {
                    toast('Failed to Add Data!','error');
                    return redirect()->back()
                                ->withErrors(["error" => "There is a duplicate data"]);
                }
                $exchangeExisting = ExchangeRate::whereDate('date', $date)
                                    ->where('to_currency_id', $from_currency)
                                    ->where('from_currency_id', $to_currency)
                                    ->get()
                                    ->first();
                if($exchangeExisting) {
                    toast('Failed to Add Data!','error');
                    return redirect()->back()
                                ->withErrors(["error" => "There is a duplicate data"]);
                }
                ExchangeRate::create($data);
            }else if($exp_operation[0] === "update"){
                $exchangeData = ExchangeRate::findOrFail($exp_operation[1]);
                if($exchangeData) {
                    $exchangeData->update($data);
                }
            }else if($exp_operation[0] === "delete") {
                $exchangeData = ExchangeRate::findOrFail($exp_operation[1]);
                $dataExists = PaymentDetail::where('currency_via_id', $exp_operation[1])->first();
                if($dataExists) {
                    toast('Failed to Delete Data!', 'error');
                    return redirect()->back()
                                ->withErrors(["error" => "There is a payment link with this data"]);
                }
                $dataExists = RecieveDetail::where('currency_via_id', $exp_operation[1])->first();
                if($dataExists) {
                    toast('Failed to Delete Data!', 'error');
                    return redirect()->back()
                                ->withErrors(["error" => "There is a receive payment link with this data"]);
                }
                if($exchangeData) {
                    $exchangeData->delete();
                }
            }
        }
       
        toast("Data Added Successfully!", 'success');
        return redirect()->route('finance.exchange-rate.index', ['date' => $date])->with('success', 'create successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        // return view('exchangerate::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // return view('exchangerate::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return redirect()->back()->with('success', 'delete successfully!');
    }

    public function getExchangeByDate(Request $request)
    {
        $date = $request->date;
        $currency = $request->currency_from;

        $exchangeFrom = ExchangeRate::where('from_currency_id', $currency)
                    ->where('date', $date)
                    ->with(['from_currency','to_currency'])
                    ->get();
        $exchangeTo = ExchangeRate::where('to_currency_id', $currency)
                        ->where('date', $date)
                        ->with(['from_currency','to_currency'])
                        ->get();
        $exchange = $exchangeFrom->concat($exchangeTo);

        return response()->json([
            "message" => "Success",
            "data" => $exchange
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
