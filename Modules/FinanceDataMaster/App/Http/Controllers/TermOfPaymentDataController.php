<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;

class TermOfPaymentDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-term@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-term@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-term@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-term@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = MasterTermOfPayment::query();

        if($search) {
            $index
            ->where('code','like',"%".$search."%")
            ->orWhere('name','like',"%".$search."%")
            ->orWhere('pay_days','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $termOfPayments = $this->searchFilterIndex($search);
        } else {
            $termOfPayments = MasterTermOfPayment::orderBy('id', 'DESC')->paginate(10);
        }
        
        return view('financedatamaster::term-of-payment.index', compact('termOfPayments'));
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
                'code'     => 'required|unique:master_term_of_payment',
                'name'   => 'required',
                'pay_days'   => 'required',
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
                'code'     => 'required|unique:master_term_of_payment,code,'.$request->id,
                'name'   => 'required',
                'pay_days'   => 'required',
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

        MasterTermOfPayment::updateOrCreate([
            'id' => $request->id
        ],
        [
            'code' => $request->code, 
            'name' => $request->name,
            'description' => $request->description,
            'pay_days' => $request->pay_days,
        ]); 

        toast('Data Saved Successfully!','success');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MasterTermOfPayment::find($id);
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
        $data = MasterTermOfPayment::find($id);
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
        $data = MasterTermOfPayment::find($id);
        $data->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
