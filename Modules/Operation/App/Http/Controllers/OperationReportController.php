<?php

namespace Modules\Operation\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OperationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-export@operation|view-import@operation');
    }

    public function index(Request $request)
    {   
        $search = $request->get('search');
        if ($search) {
            $export = new Collection();
            if(auth()->user()->can('view-export@operation')){
            $export = OperationExport::with('marketing')
                        ->where('pickup_date', $search)
                        ->orWhere('origin', $search)
                        ->orWhere('destination', $search)
                        ->orWhere('transportation_desc', $search)
                        ->orWhereHas('marketing', function ($query) use ($search){
                            $query->where('source', 'like', '%'.$search.'%')
                                  ->orWhere('total_weight', 'like', '%'.$search.'%')
                                  ->orWhere('description', 'like', '%'.$search.'%');
                        })
                        ->get();
            }

            $import = new Collection();
            if(auth()->user()->can('view-import@operation')){
            $import = OperationImport::with('marketing')
                        ->where('pickup_date', $search)
                        ->orWhere('origin', $search)
                        ->orWhere('destination', $search)
                        ->orWhere('transportation_desc', $search)
                        ->orWhereHas('marketing', function ($query) use ($search){
                            $query->where('source', 'like', '%'.$search.'%')
                                    ->orWhere('total_weight', 'like', '%'.$search.'%')
                                    ->orWhere('description', 'like', '%'.$search.'%');
                        })
                        ->get();
            }
            $mergedData = $export->concat($import);
        } else {
            $export = new Collection();
            if(auth()->user()->can('view-export@operation')){
            $export = OperationExport::with('marketing')->get();
            }
            $import = new Collection();
            if(auth()->user()->can('view-import@operation')){
            $import = OperationImport::with('marketing')->get();
            }
    
            $mergedData = $export->concat($import);
        }

        // Konversi hasil query menjadi Collection
        $collection = new Collection($mergedData);

        // Buat instance LengthAwarePaginator secara manual
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $total = $collection->count();

        $paginator = new LengthAwarePaginator($items, $total, $perPage, $currentPage, ['path' => url('operation/report')]);

        return view('operation::operation-report.index', compact('mergedData', 'paginator', 'search'));
    }

    public function filterExportInternal($dateFrom, $dateTo, $expedition, $transportation, $customer)
    {
        $export = OperationExport::query();
        $import = OperationImport::query();

        if ($dateFrom) {
            if ($dateTo) {
                $export->whereRaw(
                    "(pickup_date >= ? AND (arrival_date <= ? OR arrival_date IS NULL))", 
                    [$dateFrom, $dateTo]
                );

                $import->whereRaw(
                    "(pickup_date >= ? AND (arrival_date <= ? OR arrival_date IS NULL))", 
                    [$dateFrom, $dateTo]
                );
            } else {
                $dateTo = date('Y-m-d');
                $export->whereRaw(
                    "(pickup_date >= ? AND (arrival_date <= ? OR arrival_date IS NULL))", 
                    [$dateFrom, $dateTo]
                );

                $import->whereRaw(
                    "(pickup_date >= ? AND (arrival_date <= ? OR arrival_date IS NULL))", 
                    [$dateFrom, $dateTo]
                );
            }
        }

        if ($expedition) {
            $export->whereHas('marketing', function ($query) use ($expedition){
                $query->where('expedition', 'like', '%'.$expedition.'%');
            });

            $import->whereHas('marketing', function ($query) use ($expedition){
                $query->where('expedition', 'like', '%'.$expedition.'%');
            });
        }

        if ($transportation) {
            $export->where('transportation', $transportation);

            $import->where('transportation', $transportation);
        }

        if ($customer) {
            $export->whereHas('marketing', function ($query) use ($customer) {
                $query->whereHas('contact', function($query) use($customer) {
                    $query->where('customer_name', $customer);
                });
            });

            $import->whereHas('marketing', function ($query) use ($customer) {
                $query->whereHas('contact', function($query) use($customer) {
                    $query->where('customer_name', $customer);
                });
            });
        }

        if(auth()->user()->can('view-import@operation') && auth()->user()->can('view-export@operation')) {
            return $export->with('marketing')->get()->concat($import->with('marketing')->get());
        } else if(auth()->user()->can('view-import@operation')) {
            return $import->with('marketing')->get();
        } else if(auth()->user()->can('view-export@operation')) {
            return $export->with('marketing')->get();
        } 
    }

    public function exportPdf (Request $request)
    {
        $type = $request->get('type');

        if ($type == 'internal') {
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $expedition = $request->get('expedition');
            $transportation = $request->get('transportation');

            if ($dateFrom || $dateTo || $expedition || $transportation) {
                $mergedData = $this->filterExportInternal($dateFrom, $dateTo, $expedition, $transportation, null);
            } else {
                $export = new Collection();
                if(auth()->user()->can('view-export@operation')){
                $export = OperationExport::with('marketing')->get();
                }
                $import = new Collection();
                if(auth()->user()->can('view-import@operation')){
                $import = OperationImport::with('marketing')->get();
                }
        
                $mergedData = $export->concat($import);
            }

            //memanggil view untuk export pdf
            $pdf = app('dompdf.wrapper')->loadView('operation::operation-report.operation-report-pdf-internal',['mergedData'=>$mergedData]);
            return $pdf->stream('operation-export-internal.pdf');
        } else {
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $customer = $request->get('customer');
            $expedition = $request->get('expedition');
            $transportation = $request->get('transportation');

            if ($dateFrom || $dateTo || $customer) {
                $mergedData = $this->filterExportInternal($dateFrom, $dateTo, $expedition, $transportation, $customer);
            } else {
                $export = new Collection();
                if(auth()->user()->can('view-export@operation')){
                $export = OperationExport::with('marketing')->get();
                }
                $import = new Collection();
                if(auth()->user()->can('view-import@operation')){
                $import = OperationImport::with('marketing')->get();
                }
                $mergedData = $export->concat($import);
            }

            //memanggil view untuk export pdf
            $pdf = app('dompdf.wrapper')->loadView('operation::operation-report.operation-report-pdf-external',['mergedData'=>$mergedData]);
            return $pdf->stream('operation-export-external.pdf');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operation::create');
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
        return view('operation::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('operation::edit');
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
