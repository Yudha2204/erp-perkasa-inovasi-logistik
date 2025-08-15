<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterTax;
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
        $index = MasterTax::query();

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
            $taxes = MasterTax::orderBy('id', 'DESC')->paginate(10);
        }
        
        return view('financedatamaster::tax.index', compact('taxes'));
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
                'tax_rate'   => 'required',
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
                'tax_rate'   => 'required',
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
            'tax_rate' => $request->tax_rate,
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
        $data = MasterTax::find($id);
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
        $data = MasterTax::find($id);
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
