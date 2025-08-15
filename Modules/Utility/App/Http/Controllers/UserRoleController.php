<?php

namespace Modules\Utility\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Utility\App\Http\Requests\StoreRoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role@role|edit-role@role|delete-role@role', ['only' => ['index','show']]);
        $this->middleware('permission:create-role@role', ['only' => ['create','store']]);
        $this->middleware('permission:edit-role@role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-role@role', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */

     public function searchFilterIndex($search){
        $index = Role::query();

        if($search) {
            $index
            ->where('name','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $roles = $this->searchFilterIndex($search);
        } else {
            $roles = Role::orderBy('id','DESC')->paginate(10);
        }

        $permissions = Permission::all();
        return view('utility::user-role.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('utility::user-role.create', [
        //     'permissions' => Permission::get()
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->id) {
            //validate store
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:250|unique:roles,name',
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
                'name'     => 'required|string|unique:roles,name,'.$request->id,
               ],
               [
                 'name.unique'=> 'The level '.$request->name.' has already been taken', // custom message 
                ]
            );
    
            if ($validator->fails()) {
                toast('failed to update data!','error');
                return redirect()->back()
                            ->withErrors($validator);
            }
        }

        Role::updateOrCreate([
            'id' => $request->id
        ],
        [
            'name' => $request->name, 
        ]); 

        toast('Data Saved Successfully!','success');
        return redirect()->back();

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('utility::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Role::find($id);
        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
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
    public function destroy($id): RedirectResponse
    {
        $role = Role::find($id);
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }
        if(auth()->user()->hasRole($role->name)){
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }
        $role->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->route('utility.user-role.index');
    }
}
