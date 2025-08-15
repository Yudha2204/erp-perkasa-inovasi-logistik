<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\FinanceDataMaster\App\Models\BankAccount;

class CurrencyDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-currency@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-currency@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-currency@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-currency@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */

     public function searchFilterIndex($search){
        $index = MasterCurrency::with('banks')->query();

        if($search) {
            $index
            ->where('initial','like',"%".$search."%")
            ->orWhere('currency_name','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $currencies = $this->searchFilterIndex($search);
        } else {
            $currencies = MasterCurrency::with('banks')->orderBy('id', 'DESC')->paginate(10);
        }
        
        return view('financedatamaster::currency.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financedatamaster::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->id) {
            //validate store
            $validator = Validator::make($request->all(), [
                'initial'     => [
                    'required',
                    Rule::unique('master_currency')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'currency_name'   => 'required'
            ]);
    
            if ($validator->fails()) {
                toast('failed to add data!','error');
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $can_delete = 1;
        } else {
            //validate edit
            $validator = Validator::make($request->all(),[
                'initial'     => [
                    'required',
                    Rule::unique('master_currency')->ignore($request->id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
                ],
                'currency_name'   => 'required'
               ],
               [
                 'initial.unique'=> 'The Initial '.$request->initial.' has already been taken', // custom message 
                ]
            );
    
            if ($validator->fails()) {
                toast('failed to update data!','error');
                return redirect()->back()
                            ->withErrors($validator);
            }

            $can_delete = MasterCurrency::find($request->id)->can_delete;
        }

        $currency = MasterCurrency::updateOrCreate([
            'id' => $request->id
        ],
        [
            'initial' => $request->initial, 
            'currency_name' => $request->currency_name,
            'can_delete' => $can_delete
        ]);

        foreach($request->account_fund as $idx => $bank) {
            $bank_id = $request->bank_id[$idx] ?? null;
            $account_fund = $request->account_fund[$idx];
            $bank_name = $request->bank_name[$idx];
            $address = $request->address[$idx];
            $swift_code = $request->swift_code[$idx];

            if($account_fund || $bank_name || $address || $swift_code) {
                BankAccount::updateOrCreate([
                    'id' => $bank_id
                ],
                [
                    "currency_id" => $currency->id,
                    "account_no" => $account_fund,
                    "bank_name" => $bank_name,
                    "address" => $address,
                    "swift_code" => $swift_code
                ]);
            } else {
                if($bank_id) {
                    BankAccount::find($bank_id)->delete();
                }
            }
        }

        toast('Data Saved Successfully!','success');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MasterCurrency::find($id);
        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = MasterCurrency::with('banks')->find($id);
        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
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
        BankAccount::where('currency_id', $id)->delete();
        $currency = MasterCurrency::find($id);
        $currency->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
