<?php

namespace Modules\Notification\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\App\Models\NotificationCustom;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first();
        $role = Role::findById($role->id);
        $permissions = $role->permissions()->get()->pluck('group');
        
        $notifications = NotificationCustom::whereIn('group_name', $permissions)
                ->orderBy('updated_at', 'desc')
                ->paginate(5);
        return view('notification::index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notification::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('notification::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('notification::edit');
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

    public function getNotification(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->first();
        $role = Role::findById($role->id);
        $permissions = $role->permissions()->get()->pluck('group');
        
        $notifications = NotificationCustom::whereIn('group_name', $permissions)
                ->orderBy('updated_at', 'desc')
                ->limit(2)->get();

        return response()->json([
            "message" => "Success",
            "data" => $notifications
        ]);
    }
}
