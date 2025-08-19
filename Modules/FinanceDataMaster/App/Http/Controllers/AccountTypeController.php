<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\ClassificationAccountType;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = AccountType::query();

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
            $accountTypes = $this->searchFilterIndex($search);
        } else {
            $accountTypes = AccountType::orderBy('id', 'DESC')->paginate(10);
        }

        $classifications = ClassificationAccountType::all();

        return view('financedatamaster::account-type.index', compact('accountTypes', 'classifications'));
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
                'code'     => 'required|unique:account_type',
                'name'   => 'required',
                'report_type' => 'required',
                'normal_side' => 'required',
                'cash_flow' => 'required',
                'classification_id' => 'required'
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
                'code'     => 'required|unique:account_type,code,'.$request->id,
                'name'   => 'required',
                'report_type' => 'required',
                'normal_side' => 'required',
                'cash_flow' => 'required',
                'classification_id' => 'required'
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

        AccountType::updateOrCreate([
            'id' => $request->id
        ],
        [
            'classification_id' => $request->classification_id,
            'code' => $request->code,
            'name' => $request->name,
            'report_type' => $request->report_type,
            'normal_side' => $request->normal_side,
            'cash_flow' => $request->cash_flow,
            'can_delete' => 1
        ]);

        toast('Data Saved Successfully!','success');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = AccountType::find($id);
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
        $data = AccountType::find($id);
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
        $accountType = AccountType::find($id);
        $accountType->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
