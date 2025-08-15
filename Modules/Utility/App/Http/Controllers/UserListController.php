<?php

namespace Modules\Utility\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Utility\App\Http\Requests\StoreUserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user@user|edit-user@user|delete-user@user', ['only' => ['index','show']]);
        $this->middleware('permission:create-user@user', ['only' => ['create','store']]);
        $this->middleware('permission:edit-user@user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-user@user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = User::query();

        if($search) {
            $index->where('name','like',"%".$search."%")
                ->orWhere('username','like',"%".$search."%")
                ->orWhere('department','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $users = $this->searchFilterIndex($search);
        } else {
            $users = User::latest('id')->paginate(10);
        }
        
        return view('utility::user-list.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('utility::user-list.create', [
            'roles' => Role::pluck('name')->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->roles);

        toast('Data Added Successfully!','success');
        return redirect()->route('utility.user-list.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('utility::user-list.show', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     return view('utility::edit');
    // }
    public function edit(User $user, $id)
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        $user = User::find($id);

        return view('utility::user-list.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     //
    // }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required'
        ]);
 
        if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }
        
        $user = User::find($id);
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->department = $request->get('department');
        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }
        $user->syncRoles($request->roles);
        $user->update();

        toast('Data Update Successfully!','success');
        return redirect()->route('utility.user-list.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::find($id);

        // About if user is Super Admin or User ID belongs to Auth User
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        $user->syncRoles([]);
        $user->delete();
        toast('Data Deleted Successfully!','success');
        return redirect()->route('utility.user-list.index');
    }
}
