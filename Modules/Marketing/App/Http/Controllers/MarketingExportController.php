<?php

namespace Modules\Marketing\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Marketing\App\Http\Requests\CreateMarketingRequest;
use Modules\Marketing\App\Models\MarketingExport;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\Marketing\App\Http\Requests\CreateQuotationRequest;
use Modules\Marketing\App\Http\Requests\UpdateMarketingRequest;
use Modules\Marketing\App\Models\DimensionMarketingExport;
use Modules\Marketing\App\Models\DocumentMarketingExport;
use Modules\Marketing\App\Models\GroupQuotationMEx;
use Modules\Marketing\App\Models\ItemGroupQuotationMEx;
use Modules\Marketing\App\Models\QuotationMarketingExport;
use Modules\Operation\App\Models\OperationExport;

class MarketingExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-export@marketing', ['only' => ['index','show','showQuotation']]);
        $this->middleware('permission:create-export@marketing', ['only' => ['create','createQuotation','store','storeQuotation']]);
        $this->middleware('permission:edit-export@marketing', ['only' => ['edit','editQuotation','update','updateQuotation']]);
        $this->middleware('permission:delete-export@marketing', ['only' => ['destroy','deleteQuotation','deleteDocument']]);
    }

    //Fungsi Search dan filter
    public function searchFilterIndex($search, $filterStatus, $filterOrigin, $filterDestination){
        $index = MarketingExport::query();

        //Jika input search terisi
        if($search) {
            $index->whereHas('contact', function ($query) use ($search){
                $query->where('customer_name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('quotation', function ($query) use ($search){
               $query->where('quotation_no', 'like', '%'.$search.'%');
            })
            ->orWhere('job_order_id','like',"%".$search."%")
            ->orWhere('no_po','like',"%".$search."%")
            ->orWhere('no_cipl','like',"%".$search."%")
            ->orWhere('description','like',"%".$search."%")
            ->orWhere('origin','like',"%".$search."%")
            ->orWhere('destination','like',"%".$search."%")
            ->orWhere('pickup_address','like',"%".$search."%")
            ->orWhere('delivery_address','like',"%".$search."%");

            if (str_contains("HOLD", strtoupper($search), )) {
                $index->orWhere('status', 1);
            } 
            if (str_contains("SELLING", strtoupper($search), )) {
                $index->orWhere('status', 2);
            }
        }

        //UNTUK FILTER
        if ($filterStatus) {
            if ($filterStatus == 1) {
                $index->where('status', 1);
            } else {
                $index->where('status', 2);
            }
        }

        if ($filterOrigin) {
            $index->where('origin', $filterOrigin);
        }

        if ($filterDestination) {
            $index->where('destination', $filterDestination);
        }
    
        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $filterStatus = $request->get('filter_status');
        $filterOrigin = $request->get('filter_origin');
        $filterDestination = $request->get('filter_destination');

        if ($search || $filterStatus || $filterOrigin || $filterDestination) {
            $marketingExports = $this->searchFilterIndex($search, $filterStatus, $filterOrigin, $filterDestination); //Memanggil fungsi search dan filter
        } else {
            $marketingExports = MarketingExport::with('quotation')->orderBy('id', 'DESC')->paginate(10);
        }

        $count = MarketingExport::count();
        $filterData = MarketingExport::select('id', 'status', 'origin', 'destination')->get();

        return view('marketing::marketing-export.index', compact('count','marketingExports', 'filterData', 'search', 'filterStatus', 'filterOrigin', 'filterDestination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $marketingExportId = $request->get('marketing_export_id');
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();
        return view('marketing::marketing-export.create', compact('contact', 'marketingExportId', 'terms'));
    }

    public function createQuotation(Request $request)
    {
        $contact = MasterContact::all();
        $dataMarketingExport = MarketingExport::find($request->get('marketing_export_id'));
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        return view('marketing::marketing-export.create-quotation', compact('contact', 'dataMarketingExport', 'terms', 'currencies'));
    }

    public function getDataCustomer(Request $request)
    {
        $customer = MasterContact::with('termPaymentContacts.term_payment')->find($request->id);
        //return response
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Customer',
            'data'    => $customer
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */

    public function generateUniqueCode()
    {
        $count = MarketingExport::where('job_order_id', '!=', null)->count();
        //cek apakah sudah ada data?
        if ($count > 0) {
            $last_data = MarketingExport::where('job_order_id', '!=', null)->orderBy('job_order_id', 'desc')->first()->job_order_id;
            $removed4char = substr($last_data, -5);
            $generate_code = 'JOPILBTH' . '-' .  str_pad($removed4char + 1, 5, "0", STR_PAD_LEFT);
        } else {
            $generate_code = 'JOPILBTH' . '-' . str_pad(1, 5, "0", STR_PAD_LEFT);
        }

        return $generate_code;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //Insert marketing to Databas 
            $existing_document_marketing = [];
            if($request->marketing_export_id) {
                $existing_document_marketing = DocumentMarketingExport::where('marketing_export_id', $request->marketing_export_id)->get();
            }
            $document_marketing = $request->file('documents') ?? [];

            if(count($existing_document_marketing) + count($document_marketing) > 3) {
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents']);
            }

            if ($request->marketing_export_id) {
                $data = MarketingExport::find($request->marketing_export_id);
            } else {
                $data = new MarketingExport();
            }
                 
            if ($request->status == 2) {
                $data->job_order_id = $this->generateUniqueCode();
            }
            $data->contact_id = $request->customer_id;
            $data->expedition = $request->expedition;
            $data->transportation = $request->transportation;
            $data->transportation_desc = $request->transportation_desc;
            $data->no_po = $request->no_po;
            $data->description = $request->description;
            $data->no_cipl = $request->no_cipl;
            $data->total_weight = $request->total_weight;
            $data->total_volume = $request->total_volume;
            $data->freetext_volume = $request->freetext_volume;
            $data->origin = $request->origin;
            $data->shipper = $request->shipper;
            $data->pickup_address = $request->pickup_address;
            $data->destination = $request->destination;
            $data->consignee = $request->consignee;
            $data->delivery_address = $request->delivery_address;
            $data->remark = $request->remark;
            $data->status = $request->status;
            $data->save();

            //store dimension to database
            if ($request->panjang) {
            for($i = 0; $i < count($request->panjang); $i++){
                $dimension = DimensionMarketingExport::create([
                    'marketing_export_id' => $data->id,
                    'packages' => $request->packages[$i],
                    'length' => $request->panjang[$i],
                    'width' => $request->lebar[$i],
                    'height' => $request->tinggi[$i],
                    'input_measure' => $request->user_input[$i],
                    'qty' => $request->quantity[$i],
                ]);
                }
            }

            //insert documents
            if ($files = $request->file('documents')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocument['marketing_export_id'] = $data->id;
                    $dataDocument['document'] = $file->storeAs(
                        'marketing-export/documents',
                        $name,
                        'public'
                    );
                    DocumentMarketingExport::create($dataDocument);
                }
            }

            //create operation if status was selling
            if ($request->status == 2) {
                $operation = new OperationExport();
                $operation->marketing_export_id = $data->id;
                $operation->job_order_id = $data->job_order_id;
                $operation->delivery_address = $data->delivery_address;
                $operation->origin = $data->origin;
                $operation->destination = $data->destination;
                $operation->pickup_address = $data->pickup_address;
                $operation->transportation = $data->transportation;
                $operation->transportation_desc = $data->transportation_desc;
                $operation->recepient_name = $data->consignee;
                $operation->status = 1;
                $operation->save();
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('marketing.export.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->route('marketing.export.create');
        }
    }

    public function storeQuotation (CreateQuotationRequest $request)
    {
        DB::beginTransaction();
        try {

            if ($request->marketing_export_id) {
                $marketingExportId = $request->marketing_export_id;
            } else {
                $marketingExport = new MarketingExport();
                $marketingExport->contact_id = $request->customer;
                $marketingExport->status = 1;
                $marketingExport->save();

                $marketingExportId = $marketingExport->id;
            }

            $salesValue = str_replace(',', '', $request->total_all);

            $quotation = new QuotationMarketingExport();
            $quotation->marketing_export_id = $marketingExportId;
            $quotation->date = $request->date;
            $quotation->quotation_no = $request->quotation_no;
            $quotation->valid_until = $request->valid_until;
            $quotation->project_desc = $request->project_desc;

            $currencyId = MasterCurrency::where('initial', $request->currency)->first()->id;
            $quotation->currency_id = $currencyId;
            $quotation->sales_value = $salesValue;
            $quotation->save();
    
            $alphabet = $request->alphabet;        
            $groups = range('A', $alphabet); // Definisikan grup-grup yang mungkin ada
    
            foreach ($groups as $groupKey) {
                $descriptionKey = "description_$groupKey";
                $totalKey = "total_$groupKey";
                $remarkKey = "remark_$groupKey";
            
                if ($request->$descriptionKey) {
                    $group = GroupQuotationMEx::create([
                        'quotation_m_ex_id' => $quotation->id,
                        'group' => $groupKey,
                    ]);
            
                    // Insert items group quotation to database
                    for ($i = 0; $i < count($request->$descriptionKey); $i++) {
                        $item = ItemGroupQuotationMEx::create([
                            'group_quotation_m_ex_id' => $group->id,
                            'description' => $request->$descriptionKey[$i],
                            'total' => str_replace(',', '', $request->$totalKey[$i]),
                            'remark' => $request->$remarkKey[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('marketing.export.index');
        } 
        catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->route('marketing.export.create-quotation');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MarketingExport::with('documents', 'dimensions')->findOrFail($id);

        return view('marketing::marketing-export.show', compact('data'));
    }

    public function editQuotation($id)
    {
        $marketingExport = MarketingExport::with('quotation')->findOrFail($id);
        $quotation = QuotationMarketingExport::where('marketing_export_id', $id)->first();
        $group = GroupQuotationMEx::where('quotation_m_ex_id', $quotation->id)->with('items')->get();
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();

        // convert to json
        $json_group = json_encode($group, true);

        return view('marketing::marketing-export.edit-quotation', compact('marketingExport', 'quotation', 'group', 'json_group', 'contact', 'terms', 'currencies'));
    }

    public function showQuotation($id)
    {
        $quotation = QuotationMarketingExport::where('marketing_export_id', $id)->firstOrFail();
        $marketingExport = MarketingExport::find($id);
        $group = GroupQuotationMEx::where('quotation_m_ex_id', $quotation->id)->get();
        $totalGroup = $group->count();
        return view('marketing::marketing-export.show-quotation', compact('quotation', 'marketingExport', 'group', 'totalGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {        
        $marketingExport = MarketingExport::with('quotation', 'dimensions')->findOrFail($id);
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();

        return view('marketing::marketing-export.edit', compact('marketingExport', 'contact', 'terms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarketingRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            //update marketing to Database
            $existing_document_marketing = DocumentMarketingExport::where('marketing_export_id', $id)->get();
            $document_marketing = $request->file('documents') ?? [];

            if(count($existing_document_marketing) + count($document_marketing) > 3) {
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents']);
            }
            $data = MarketingExport::find($id);
            $data->contact_id = $request->customer_id;
            $data->expedition = $request->expedition;
            $data->transportation = $request->transportation;
            if ($request->transportation == 3) {
                $data->transportation_desc = null;
            } else {
                $data->transportation_desc = $request->transportation_desc;
            }
            $data->no_po = $request->no_po;
            $data->description = $request->description;
            $data->no_cipl = $request->no_cipl;
            $data->total_weight = $request->total_weight;
            $data->total_volume = $request->total_volume;
            $data->freetext_volume = $request->freetext_volume;
            $data->origin = $request->origin;
            $data->shipper = $request->shipper;
            $data->pickup_address = $request->pickup_address;
            $data->destination = $request->destination;
            $data->consignee = $request->consignee;
            $data->delivery_address = $request->delivery_address;
            $data->remark = $request->remark;
            $data->status = $request->status;
            $data->total_volume = $request->total_volume_edit;
            if ($request->status == 2 && $data->job_order_id == null) {
                $data->job_order_id = $this->generateUniqueCode();
            }
            $data->save();

            //delete data before for dimension
            $dimension = DimensionMarketingExport::where('marketing_export_id', $id);
            $dimension->forceDelete();

            //create new data dimension
            if ($request->panjang) {
                if ($request->panjang[0] != null) {
                    for($i = 0; $i < count($request->panjang); $i++){
                        $dimension = DimensionMarketingExport::create([
                            'marketing_export_id' => $id,
                            'packages' => $request->packages[$i],
                            'length' => $request->panjang[$i],
                            'width' => $request->lebar[$i],
                            'height' => $request->tinggi[$i],
                            'input_measure' => $request->user_input[$i],
                            'qty' => $request->quantity[$i],
                        ]);
                    }
                }
            }

            //update documents
            if ($files = $request->file('documents')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocument['marketing_export_id'] = $data->id;
                    $dataDocument['document'] = $file->storeAs(
                        'marketing-export/documents',
                        $name,
                        'public'
                    );
                    DocumentMarketingExport::create($dataDocument);
                }
            }

            //create operation if status was selling
            if ($request->status == 2) {
                //cek dulu di operation apakah data marketing sudah masuk kesitu, jika blm maka buat, kalau jika sudah maka jgn lakukan apa2
                $checkOperation = OperationExport::where('marketing_export_id', $data->id)->first();

                if ($checkOperation) {
                    //do nothing
                } else {
                    $operation = new OperationExport();
                    $operation->marketing_export_id = $data->id;
                    $operation->job_order_id = $data->job_order_id;
                    $operation->delivery_address = $data->delivery_address;
                    $operation->origin = $data->origin;
                    $operation->destination = $data->destination;
                    $operation->pickup_address = $data->pickup_address;
                    $operation->transportation = $data->transportation;
                    $operation->transportation_desc = $data->transportation_desc;
                    $operation->recepient_name = $data->consignee;
                    $operation->status = 1;
                    $operation->save();
                }
            } else if ($request->status == 1) {
                //delete operation if status not selling 
                $operation = OperationExport::where('marketing_export_id', $data->id);
                $operation->forceDelete();
            }

            DB::commit();
            toast('Data Updated Successfully!','success');
            return redirect()->route('marketing.export.index');
        } catch (\Exception $e) {
            toast('Data Update Failed!','error');
            return redirect()->back();
        }
    }

    public function updateQuotation(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $quotation = QuotationMarketingExport::find($request->quotation_id);
            $quotation->date = $request->date;
            $quotation->quotation_no = $request->quotation_no;
            $quotation->valid_until = $request->valid_until;
            $quotation->project_desc = $request->project_desc;

            $currencyId = MasterCurrency::where('initial', $request->currency)->first()->id;
            $quotation->currency_id = $currencyId;
            $quotation->save();


            //update sales
            //update total sales value to quotation
            $quotationb = QuotationMarketingExport::find($request->quotation_id);
            $salesValue = str_replace(',', '', $request->total_all);
            $quotationb->sales_value = $salesValue;
            $quotationb->save();

            //delete data before
            $groupSales = GroupQuotationMEx::where('quotation_m_ex_id', $request->quotation_id);

            $groupSales->forceDelete();

            $itemSales = ItemGroupQuotationMEx::where('group_quotation_m_ex_id', null);
            $itemSales->forceDelete();

            $alphabet = $request->alphabet;
            $groups = range('A', $alphabet); // Definisikan grup-grup yang mungkin ada

            foreach ($groups as $groupKey) {
                $descriptionKey = "description_$groupKey";
                $totalKey = "total_$groupKey";
                $remarkKey = "remark_$groupKey";

                if ($request->$descriptionKey) {
                    $group = GroupQuotationMEx::create([
                        'quotation_m_ex_id' => $quotation->id,
                        'group' => $groupKey,
                    ]);

                    // Insert items group quotation to database
                    for ($i = 0; $i < count($request->$descriptionKey); $i++) {
                        $item = ItemGroupQuotationMEx::create([
                            'group_quotation_m_ex_id' => $group->id,
                            'description' => $request->$descriptionKey[$i],
                            'total' => str_replace(',', '', $request->$totalKey[$i]),
                            'remark' => $request->$remarkKey[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            toast('Data Updated Successfully!','success');
            return redirect()->route('marketing.export.index');
        } 
            catch (\Exception $e) {
            toast('Data Update Failed!','error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marketingExport = MarketingExport::find($id);
        //make  job order id null
        $data = MarketingExport::find($id);
        $data->job_order_id = null;
        $data->save();

        $marketingExport->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->route('marketing.export.index');
    }

    public function deleteDocument(Request $request)
    {
        $document = DocumentMarketingExport::find($request->delete_document_id);
        if (file_exists(storage_path('app/public/' . $document->document))) {
            unlink(storage_path('app/public/'.$document->document));
        }
        $document->forceDelete();

        toast('Document Deleted Successfully!','success');
        return redirect()->back();
    }

    public function deleteQuotation(Request $request)
    {
        DB::beginTransaction();
        try {
            $quotation = QuotationMarketingExport::find($request->quotation_id);
            $quotation->forceDelete();

            $groupSales = GroupQuotationMEx::where('quotation_m_ex_id', $request->quotation_id);
            $groupSales->forceDelete();

            $itemSales = ItemGroupQuotationMEx::where('group_quotation_m_ex_id', null);
            $itemSales->forceDelete();

            DB::commit();
            toast('Quotation Deleted Successfully!','success');
            return redirect()->back();
        } 
            catch (\Exception $e) {
            toast('Quotation Deleted Failed!','success');
            return redirect()->back();
        }
    }


}
