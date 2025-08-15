<?php

namespace Modules\RealtimeTracking\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Modules\Operation\App\Models\ProgressOperationExport;
use Modules\Operation\App\Models\ProgressOperationImport;

class RealtimeTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $export = new Collection();
            $export = OperationExport::with('marketing', 'activity')
            ->whereHas('activity', function ($query) use ($search){
                $query->where('return_pod_date', 'like', '%'.$search.'%');
            })
            ->orWhereHas('marketing', function ($query) use ($search){
                $query->where('source', 'like', '%'.$search.'%');
            })
            ->orWhere('job_order_id','like',"%".$search."%")
            ->orWhere('departure_date','like',"%".$search."%")
            ->orWhere('transportation_desc','like',"%".$search."%")
            ->orWhere('origin','like',"%".$search."%")
            ->orWhere('destination','like',"%".$search."%")
            ->get();

            $import = new Collection();
            $import = OperationImport::with('marketing', 'activity')
            ->whereHas('activity', function ($query) use ($search){
                $query->where('return_pod_date', 'like', '%'.$search.'%');
            })
            ->orWhereHas('marketing', function ($query) use ($search){
                $query->where('source', 'like', '%'.$search.'%');
            })
            ->orWhere('job_order_id','like',"%".$search."%")
            ->orWhere('departure_date','like',"%".$search."%")
            ->orWhere('transportation_desc','like',"%".$search."%")
            ->orWhere('origin','like',"%".$search."%")
            ->orWhere('destination','like',"%".$search."%")
            ->get();
        } else {
            $export = new Collection();
            $export = OperationExport::with('marketing', 'activity')->get();
            $import = new Collection();
            $import = OperationImport::with('marketing', 'activity')->get();
        }

        $mergedData = $export->concat($import);

        return view('realtimetracking::realtime-tracking.index', compact('mergedData', 'search'));
    }

    public function detail($category, $id)
    {
        if ($category == "export") {
            $operation = OperationExport::with('marketing', 'activity')->find($id);
            $progress = ProgressOperationExport::where('operation_export_id', $id)->get();
        } else {
            $operation = OperationImport::with('marketing', 'activity')->find($id);
            $progress = ProgressOperationImport::where('operation_import_id', $id)->get();
        }
        return view('realtimetracking::realtime-tracking.detail', compact('operation', 'progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('realtimetracking::create');
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
        return view('realtimetracking::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('realtimetracking::edit');
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
}
