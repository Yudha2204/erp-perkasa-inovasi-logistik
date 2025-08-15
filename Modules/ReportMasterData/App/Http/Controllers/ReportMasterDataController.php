<?php

namespace Modules\ReportMasterData\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\FinanceDataMaster\App\Models\MasterContact;

class ReportMasterDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $export = new Collection();
        $export = OperationExport::with('marketing')->get();
        $import = new Collection();
        $import = OperationImport::with('marketing')->get();
        $fullData = $export->concat($import);
        $domestic = 0;
        $international = 0;
        foreach ($fullData as $operation) {
            if($operation->marketing->expedition == 1){
                $domestic++;
            }else{
                $international++;
            }
        }
        $collection = new Collection($fullData);
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $total = $collection->count();
        $paginator = new LengthAwarePaginator($items, $total, $perPage, $currentPage, ['path' => url('dashboard')]);

        $domesticExport = new Collection();
        $domesticExport = OperationExport::whereHas('marketing', function ($query) {
                $query->where('expedition', 1);
            })->with('marketing')->get();
        $domesticImport = new Collection();
        $domesticImport = OperationImport::whereHas('marketing', function ($query) {
                $query->where('expedition', 1);
            })->with('marketing')->get();
        $domesticMergedData = $domesticExport->concat($domesticImport);
        $domesticCollection = new Collection($domesticMergedData);

        $domesticPerPage = 10;
        $domesticCurrentPage = LengthAwarePaginator::resolveCurrentPage();
        $domesticItems = $domesticCollection->slice(($domesticCurrentPage - 1) * $domesticPerPage, $domesticPerPage)->all();
        $domesticTotal = $domesticCollection->count();

        $domesticperPaginator = new LengthAwarePaginator($domesticItems, $domesticTotal, $domesticPerPage, $domesticCurrentPage, ['path' => url('dashboard')]);


        $internationalExport = new Collection();
        $internationalExport = OperationExport::whereHas('marketing', function ($query) {
            $query->where('expedition', 2);
        })->with('marketing')->get();
        $internationalImport = new Collection();
        $internationalImport = OperationImport::whereHas('marketing', function ($query) {
                $query->where('expedition', 2);
            })->with('marketing')->get();
        $internationalMergedData = $internationalExport->concat($internationalImport);
        $internationalCollection = new Collection($internationalMergedData);

        $internationalPerPage = 10;
        $internationalCurrentPage = LengthAwarePaginator::resolveCurrentPage();
        $internationalItems = $internationalCollection->slice(($internationalCurrentPage - 1) * $internationalPerPage, $internationalPerPage)->all();
        $internationalTotal = $internationalCollection->count();

        $internationalperPaginator = new LengthAwarePaginator($internationalItems, $internationalTotal, $internationalPerPage, $internationalCurrentPage, ['path' => url('dashboard')]);

        return view('reportmasterdata::index', compact('fullData', 'domesticperPaginator', 'internationalperPaginator','domestic', 'international', 'paginator'));
    }
    public function getDataInternational()
    {
        $internationalExport = new Collection();
        $internationalExport = OperationExport::whereHas('marketing', function ($query) {
            $query->where('expedition', 2);
        })->with('marketing')->get();
        $internationalImport = new Collection();
        $internationalImport = OperationImport::whereHas('marketing', function ($query) {
                $query->where('expedition', 2);
            })->with('marketing')->get();
        $internationalMergedData = $internationalExport->concat($internationalImport);

        return response()->json($internationalMergedData);
    }
    public function getDataDomestic()
    {
        $domesticExport = new Collection();
        $domesticExport = OperationExport::whereHas('marketing', function ($query) {
                $query->where('expedition', 1);
            })->with('marketing')->get();
        $domesticImport = new Collection();
        $domesticImport = OperationImport::whereHas('marketing', function ($query) {
                $query->where('expedition', 1);
            })->with('marketing')->get();
        $domesticMergedData = $domesticExport->concat($domesticImport);

        return response()->json($domesticMergedData);
    }

    public function getBarData()
    {
        // Data Internasional
        $internationalExport = new Collection();
            $internationalExport = OperationExport::whereHas('marketing', function ($query) {
                $query->where('expedition', 2);
            })->with('marketing')->get();
        $internationalImport = new Collection();
        $internationalImport = OperationImport::whereHas('marketing', function ($query) {
            $query->where('expedition', 2);
        })->with('marketing')->get();
        
        $internationalMergedData = $internationalExport->concat($internationalImport);

        $international = [
            'Sun' => 0,
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
        ];

        foreach ($internationalMergedData as $operation) {
            $dayOfWeek = date('D', strtotime($operation->created_at));
            $international[$dayOfWeek]++;
        }

        // Data Domestik
        $domesticExport = new Collection();
        $domesticExport = OperationExport::whereHas('marketing', function ($query) {
            $query->where('expedition', 1);
        })->with('marketing')->get();
        $domesticImport = new Collection();
        $domesticImport = OperationImport::whereHas('marketing', function ($query) {
            $query->where('expedition', 1);
        })->with('marketing')->get();
        
        $domesticMergedData = $domesticExport->concat($domesticImport);

        $domestic = [
            'Sun' => 0,
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
        ];

        foreach ($domesticMergedData as $operation) {
            $dayOfWeek = date('D', strtotime($operation->created_at));
            $domestic[$dayOfWeek]++;
        }

        return response()->json([
            'international' => $international,
            'domestic' => $domestic,
        ]);
    }

    public function getDataByDate(Request $request)
    {
        $start = $request->startDate;
        $end = $request->endDate;
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start || $end){
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate)); 
            $endDate = Carbon::parse($endDate)->addDays(1)->format('Y-m-d');
        }

        $domesticExport = new Collection();
        $domesticExport = OperationExport::whereHas('marketing', function ($query) {
            $query->where('expedition', 1);
        })->with('marketing')->whereBetween('created_at', [$startDate, $endDate])->get();
        $domesticExport = $domesticExport->map(function ($item) {
            $data = MasterContact::find($item->marketing->contact_id);
            $item->user = $data;
            return $item;
        });

        $domesticImport = new Collection();
        $domesticImport = OperationImport::whereHas('marketing', function ($query) {
            $query->where('expedition', 1);
        })->with('marketing')->whereBetween('created_at', [$startDate, $endDate])->get();
        $domesticImport = $domesticImport->map(function ($item) {
            $data = MasterContact::find($item->marketing->contact_id);
            $item->user = $data;
            return $item;
        });

        $domesticMergedData = $domesticExport->concat($domesticImport);
        $domesticCollection = new Collection($domesticMergedData);
        $domesticPerPage = 10;
        $domesticCurrentPage = LengthAwarePaginator::resolveCurrentPage();
        $domesticItems = $domesticCollection->slice(($domesticCurrentPage - 1) * $domesticPerPage, $domesticPerPage)->all();
        $domesticTotal = $domesticCollection->count();

        $domesticperPaginator = new LengthAwarePaginator($domesticItems, $domesticTotal, $domesticPerPage, $domesticCurrentPage, ['path' => url('dashboard')]);

        $internationalExport = new Collection();
        $internationalExport = OperationExport::whereHas('marketing', function ($query) {
            $query->where('expedition', 2);
        })->with('marketing')->whereBetween('created_at', [$startDate, $endDate])->get();
        $internationalExport = $internationalExport->map(function ($item) {
            $data = MasterContact::find($item->marketing->contact_id);
            $item->user = $data;
            return $item;
        });

        $internationalImport = new Collection();
        $internationalImport = OperationImport::whereHas('marketing', function ($query) {
            $query->where('expedition', 2);
        })->with('marketing')->whereBetween('created_at', [$startDate, $endDate])->get();
        $internationalImport = $internationalImport->map(function ($item) {
            $data = MasterContact::find($item->marketing->contact_id);
            $item->user = $data;
            return $item;
        });

        $internationalMergedData = $internationalExport->concat($internationalImport);
        $internationalCollection = new Collection($internationalMergedData);
        $internationalPerPage = 10;
        $internationalCurrentPage = LengthAwarePaginator::resolveCurrentPage();
        $internationalItems = $internationalCollection->slice(($internationalCurrentPage - 1) * $internationalPerPage, $internationalPerPage)->all();
        $internationalTotal = $internationalCollection->count();

        $internationalperPaginator = new LengthAwarePaginator($internationalItems, $internationalTotal, $internationalPerPage, $internationalCurrentPage, ['path' => url('dashboard')]);


        // bar
        // Data International
        $international = [
            'Sun' => 0,
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
        ];

        foreach ($internationalMergedData as $operation) {
            $dayOfWeek = date('D', strtotime($operation->created_at));
            $international[$dayOfWeek]++;
        }

        // Data Domestik
        $domestic = [
            'Sun' => 0,
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
        ];

        foreach ($domesticMergedData as $operation) {
            $dayOfWeek = date('D', strtotime($operation->created_at));
            $domestic[$dayOfWeek]++;
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'domesticperPaginator' => $domesticperPaginator,
            'internationalperPaginator' => $internationalperPaginator,
            'international' => $international, 
            'domestic' => $domestic, 
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reportmasterdata::create');
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
        return view('reportmasterdata::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('reportmasterdata::edit');
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
        // Log::info("destroy"); 
    }
}
