<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterTax;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Illuminate\Support\Facades\Validator;

class TaxDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-tax@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-tax@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-tax@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-tax@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = MasterTax::with(['account', 'salesAccount']);

        if($search) {
            $index
            ->where('code','like',"%".$search."%")
            ->orWhere('name','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $taxes = $this->searchFilterIndex($search);
        } else {
            $taxes = MasterTax::with(['account', 'salesAccount'])->orderBy('id', 'DESC')->paginate(10);
        }
        
        // Get accounts for dropdown - will be filtered dynamically in view based on tax type
        $accounts = MasterAccount::orderBy('code', 'ASC')->where('type', 'detail')->get();
        
        return view('financedatamaster::tax.index', compact('taxes', 'accounts'));
    }

    /**
     * Get accounts filtered by account type code
     */
    public function getAccountsByType(Request $request)
    {
        $taxType = $request->get('tax_type');
        $accountType = $request->get('account_type'); // 'purchase' or 'sales'

        $accountTypeCode = null;
        
        if ($taxType == 'PPN') {
            if ($accountType == 'purchase') {
                // Tax In (code: 1-0008)
                $accountTypeCode = '1-0008';
            } else if ($accountType == 'sales') {
                // Tax Out (code: 2-0005)
                $accountTypeCode = '2-0005';
            }
        } else if ($taxType == 'PPH') {
            if ($accountType == 'purchase') {
                // Other Payable (code: 2-0002)
                $accountTypeCode = '2-0002';
            } else if ($accountType == 'sales') {
                // Expense (code: 6-0000)
                $accountTypeCode = '6-0000';
            }
        }

        if ($accountTypeCode) {
            $accountTypeModel = AccountType::where('code', $accountTypeCode)->first();
            
            if ($accountTypeModel) {
                $accounts = MasterAccount::where('account_type_id', $accountTypeModel->id)
                    ->where('type', 'detail')
                    ->orderBy('code', 'ASC')
                    ->get();
                
                return response()->json([
                    'success' => true,
                    'accounts' => $accounts
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'accounts' => []
        ]);
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
                'code'     => 'required|unique:master_tax',
                'name'   => 'required',
                'type'   => 'required|in:PPN,PPH',
                'tax_rate'   => 'required',
                'account_id' => 'nullable|exists:master_account,id',
                'sales_account_id' => 'nullable|exists:master_account,id',
            ]);
    
            if ($validator->fails()) {
                toast('failed to add data!','error');
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }
        } else {
            //validate edit
            $validator = Validator::make($request->all(),[
                'code'     => 'required|unique:master_tax,code,'.$request->id,
                'name'   => 'required',
                'type'   => 'required|in:PPN,PPH',
                'tax_rate'   => 'required',
                'account_id' => 'nullable|exists:master_account,id',
                'sales_account_id' => 'nullable|exists:master_account,id',
               ],
               [
                 'code.unique'=> 'The code '.$request->code.' has already been taken', // custom message 
                ]
            );
    
            if ($validator->fails()) {
                toast('failed to update data!','error');
                return redirect()->back()
                            ->withErrors($validator);
            }
        }

        MasterTax::updateOrCreate([
            'id' => $request->id
        ],
        [
            'code' => $request->code, 
            'name' => $request->name,
            'type' => $request->type,
            'tax_rate' => $request->tax_rate,
            'account_id' => $request->account_id, // Purchase Account
            'sales_account_id' => $request->sales_account_id, // Sales Account
            'status' => $request->status,
        ]); 

        toast('Data Saved Successfully!','success');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MasterTax::with(['account', 'salesAccount'])->find($id);
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
        $data = MasterTax::with(['account', 'salesAccount'])->find($id);
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
        $tax = MasterTax::find($id);
        $tax->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
