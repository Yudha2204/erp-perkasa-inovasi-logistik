<?php

namespace Modules\Utility\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UtilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('utility::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('utility::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $role = Role::findById($request->role_id);
        if($role->name=='Super Admin'){
            return redirect()->back()->withErrors(["error" => "Can not set permissions to Super Admin"]);
        }
        if(auth()->user()->hasRole($role->name)){
            return redirect()->back()->withErrors(["error" => "Can not set permissions to self role"]);
        }

        $role->syncPermissions($request->permissions);
        toast('Data Saved Successfully!', 'success');
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
        return view('utility::edit');
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

    public function getUserPermission(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permissions = $role->permissions()->get();
        
        return response()->json([
            "message" => "Success",
            "data" => $permissions
        ]);
    }
}
