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

    /**
     * Check if exchange rate exists for specific currency pair and date range
     */
    public function checkExistingExchangeRates(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $currencyPairs = $request->input('currency_pairs', []);

        
        $existingRates = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        foreach ($currencyPairs as $pair) {
            if (is_string($pair) && strpos($pair, '-') !== false) {
                [$fromCurrency, $toCurrency] = explode('-', $pair, 2);
            } else {
                $fromCurrency = $pair['from_currency'] ?? null;
                $toCurrency = $pair['to_currency'] ?? null;
            }
            
            $currentDate = $start->copy();

            while ($currentDate->lte($end)) {
                $existingExchange = ExchangeRate::whereDate('date', $currentDate->format('Y-m-d'))
                    ->where(function($query) use ($fromCurrency, $toCurrency) {
                        $query->where(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $fromCurrency)
                              ->where('to_currency_id', $toCurrency);
                        })->orWhere(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $toCurrency)
                              ->where('to_currency_id', $fromCurrency);
                        });
                    })
                    ->first();

                if ($existingExchange) {
                    $existingRates[] = [
                        'date' => $currentDate->format('Y-m-d'),
                        'from_currency' => $fromCurrency,
                        'to_currency' => $toCurrency,
                        'existing_rate' => $existingExchange
                    ];
                }

                $currentDate->addDay();
            }
        }

        return response()->json([
            'message' => 'Success',
            'existing_rates' => $existingRates,
            'has_conflicts' => count($existingRates) > 0
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }

    /**
     * Store bulk exchange rates for multiple currency pairs in a date range
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'bulk_start_date' => 'required|date',
            'bulk_end_date' => 'required|date|after_or_equal:bulk_start_date',
            'currency_pairs' => 'required|array|min:1',
            'currency_pairs.*.from_currency' => 'required|exists:master_currency,id',
            'currency_pairs.*.to_currency' => 'required|exists:master_currency,id',
            'currency_pairs.*.from_nominal' => 'required|numeric|min:0.01',
            'currency_pairs.*.to_nominal' => 'required|numeric|min:0.01',
        ]);

        $startDate = Carbon::parse($request->input('bulk_start_date'));
        $endDate = Carbon::parse($request->input('bulk_end_date'));
        $currencyPairs = $request->input('currency_pairs');

        // Validate currency pairs (no same currency, no duplicates)
        $validatedPairs = [];
        foreach ($currencyPairs as $index => $pair) {
            if ($pair['from_currency'] === $pair['to_currency']) {
                toast('From Currency and To Currency cannot be the same!', 'error');
                return redirect()->back()->withErrors(["error" => "From Currency and To Currency cannot be the same in pair " . ($index + 1)]);
            }

            // Check for duplicate pairs (bidirectional)
            $pairKey = $pair['from_currency'] . '-' . $pair['to_currency'];
            $reversePairKey = $pair['to_currency'] . '-' . $pair['from_currency'];
            
            if (in_array($pairKey, $validatedPairs)) {
                toast('Duplicate currency pair detected!', 'error');
                return redirect()->back()->withErrors(["error" => "Duplicate currency pair detected in pair " . ($index + 1) . " - same direction already exists"]);
            }
            
            if (in_array($reversePairKey, $validatedPairs)) {
                toast('Duplicate currency pair detected!', 'error');
                return redirect()->back()->withErrors(["error" => "Duplicate currency pair detected in pair " . ($index + 1) . " - reverse direction already exists"]);
            }
            
            $validatedPairs[] = $pairKey;
        }

        // Pre-check for existing rates to provide detailed feedback
        $existingRatesDetails = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            foreach ($currencyPairs as $pairIndex => $pair) {
                $fromCurrency = $pair['from_currency'];
                $toCurrency = $pair['to_currency'];
                
                $existingExchange = ExchangeRate::whereDate('date', $currentDate->format('Y-m-d'))
                    ->where(function($query) use ($fromCurrency, $toCurrency) {
                        $query->where(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $fromCurrency)
                              ->where('to_currency_id', $toCurrency);
                        })->orWhere(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $toCurrency)
                              ->where('to_currency_id', $fromCurrency);
                        });
                    })
                    ->with(['from_currency', 'to_currency'])
                    ->first();

                if ($existingExchange) {
                    // Check if it's the same direction or reverse direction
                    $isSameDirection = ($existingExchange->from_currency_id == $fromCurrency && $existingExchange->to_currency_id == $toCurrency);
                    $direction = $isSameDirection ? 'same' : 'reverse';
                    
                    $existingRatesDetails[] = [
                        'date' => $currentDate->format('Y-m-d'),
                        'pair_index' => $pairIndex + 1,
                        'from_currency' => $existingExchange->from_currency->initial,
                        'to_currency' => $existingExchange->to_currency->initial,
                        'existing_from_nominal' => $existingExchange->from_nominal,
                        'existing_to_nominal' => $existingExchange->to_nominal,
                        'direction' => $direction,
                        'requested_from' => $fromCurrency,
                        'requested_to' => $toCurrency
                    ];
                }
            }
            $currentDate->addDay();
        }

        // If there are existing rates, show detailed information
        if (!empty($existingRatesDetails)) {
            $conflictMessage = "The following exchange rates already exist and will be skipped:\n\n";
            foreach ($existingRatesDetails as $detail) {
                $conflictMessage .= "• Pair {$detail['pair_index']}: {$detail['from_currency']} → {$detail['to_currency']} on {$detail['date']} (Current: {$detail['existing_from_nominal']} → {$detail['existing_to_nominal']})\n";
            }
            $conflictMessage .= "\nDo you want to continue and create only the new exchange rates?";
            
            // For now, we'll continue but show the conflicts in the success message
        }

        $totalCreatedCount = 0;
        $totalSkippedCount = 0;
        $currentDate = $startDate->copy();

        // Process each date in the range
        while ($currentDate->lte($endDate)) {
            // Process each currency pair for this date
            foreach ($currencyPairs as $pair) {
                $fromCurrency = $pair['from_currency'];
                $toCurrency = $pair['to_currency'];
                $fromNominal = $this->numberToDatabase($pair['from_nominal']);
                $toNominal = $this->numberToDatabase($pair['to_nominal']);

                // Check if exchange rate already exists for this date and currency pair
                $existingExchange = ExchangeRate::whereDate('date', $currentDate->format('Y-m-d'))
                    ->where(function($query) use ($fromCurrency, $toCurrency) {
                        $query->where(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $fromCurrency)
                              ->where('to_currency_id', $toCurrency);
                        })->orWhere(function($q) use ($fromCurrency, $toCurrency) {
                            $q->where('from_currency_id', $toCurrency)
                              ->where('to_currency_id', $fromCurrency);
                        });
                    })
                    ->first();

                if (!$existingExchange) {
                    ExchangeRate::create([
                        'date' => $currentDate->format('Y-m-d'),
                        'from_currency_id' => $fromCurrency,
                        'from_nominal' => $fromNominal,
                        'to_currency_id' => $toCurrency,
                        'to_nominal' => $toNominal,
                    ]);
                    $totalCreatedCount++;
                } else {
                    $totalSkippedCount++;
                }
            }
            
            $currentDate->addDay();
        }

        $pairCount = count($currencyPairs);
        $dayCount = $startDate->diffInDays($endDate) + 1;
        
        $message = "Bulk exchange rate created successfully! Created: {$totalCreatedCount} records ({$pairCount} currency pairs × {$dayCount} days)";
        if ($totalSkippedCount > 0) {
            $message .= ", Skipped: {$totalSkippedCount} records (already exist)";
            
            // Add detailed information about existing rates
            if (!empty($existingRatesDetails)) {
                $message .= "\n\nExisting rates found:";
                foreach (array_slice($existingRatesDetails, 0, 5) as $detail) { // Show first 5 conflicts
                    $directionText = $detail['direction'] == 'same' ? 'same direction' : 'reverse direction';
                    $message .= "\n• {$detail['from_currency']} → {$detail['to_currency']} on {$detail['date']} ({$directionText})";
                }
                if (count($existingRatesDetails) > 5) {
                    $message .= "\n• ... and " . (count($existingRatesDetails) - 5) . " more";
                }
            }
        }

        toast($message, 'success');
        return redirect()->route('finance.exchange-rate.index', ['date' => $startDate->format('Y-m-d')])
            ->with('success', $message);
    }
}
