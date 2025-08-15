<?php

namespace Modules\Marketing\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Marketing\App\Http\Requests\CreateMarketingRequest;
use Modules\Marketing\App\Models\MarketingImport;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\Marketing\App\Http\Requests\CreateQuotationRequest;
use Modules\Marketing\App\Http\Requests\UpdateMarketingRequest;
use Modules\Marketing\App\Models\DimensionMarketingImport;
use Modules\Marketing\App\Models\DocumentMarketingImport;
use Modules\Marketing\App\Models\GroupQuotationMIm;
use Modules\Marketing\App\Models\ItemGroupQuotationMIm;
use Modules\Marketing\App\Models\QuotationMarketingImport;
use Modules\Operation\App\Models\OperationImport;

class MarketingImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-import@marketing', ['only' => ['index','show','showQuotation']]);
        $this->middleware('permission:create-import@marketing', ['only' => ['create','createQuotation','store','storeQuotation']]);
        $this->middleware('permission:edit-import@marketing', ['only' => ['edit','editQuotation','update','updateQuotation']]);
        $this->middleware('permission:delete-import@marketing', ['only' => ['destroy','deleteQuotation','deleteDocument']]);
    }

    //Fungsi Search dan filter
    public function searchFilterIndex($search, $filterStatus, $filterOrigin, $filterDestination){
        $index = MarketingImport::query();

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
            $marketingImports = $this->searchFilterIndex($search, $filterStatus, $filterOrigin, $filterDestination); //Memanggil fungsi search dan filter
        } else {
            $marketingImports = MarketingImport::with('quotation')->orderBy('id', 'DESC')->paginate(10);
        }

        $count = MarketingImport::count();
        $filterData = MarketingImport::select('id', 'status', 'origin', 'destination')->get();

        return view('marketing::marketing-import.index', compact('count','marketingImports', 'filterData', 'search', 'filterStatus', 'filterOrigin', 'filterDestination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $marketingImportId = $request->get('marketing_import_id');
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();
        return view('marketing::marketing-import.create', compact('contact', 'marketingImportId', 'terms'));
    }

    public function createQuotation(Request $request)
    {
        $contact = MasterContact::all();
        $dataMarketingImport = MarketingImport::find($request->get('marketing_import_id'));
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        return view('marketing::marketing-import.create-quotation', compact('contact', 'dataMarketingImport', 'terms', 'currencies'));
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
        $count = MarketingImport::where('job_order_id', '!=', null)->count();
        //cek apakah sudah ada data?
        if ($count > 0) {
            $last_data = MarketingImport::where('job_order_id', '!=', null)->orderBy('job_order_id', 'desc')->first()->job_order_id;
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
            //Insert marketing to Database
            $existing_document_marketing = [];
            if($request->marketing_export_id) {
                $existing_document_marketing = DocumentMarketingImport::where('marketing_import_id', $request->marketing_export_id)->get();
            }
            $document_marketing = $request->file('documents') ?? [];

            if(count($existing_document_marketing) + count($document_marketing) > 3) {
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents']);
            }

            if ($request->marketing_import_id) {
                $data = MarketingImport::find($request->marketing_import_id);
            } else {
                $data = new MarketingImport();
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
                $dimension = DimensionMarketingImport::create([
                    'marketing_import_id' => $data->id,
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
                    $dataDocument['marketing_import_id'] = $data->id;
                    $dataDocument['document'] = $file->storeAs(
                        'marketing-import/documents',
                        $name,
                        'public'
                    );
                    DocumentMarketingImport::create($dataDocument);
                }
            }

            //create operation if status was selling
            if ($data->status == 2) {
                $operation = new OperationImport();
                $operation->marketing_import_id = $data->id;
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
            return redirect()->route('marketing.import.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->route('marketing.import.create');
        }
    }

    public function storeQuotation (CreateQuotationRequest $request)
    {
        DB::beginTransaction();
        try {

            if ($request->marketing_import_id) {
                $marketingImportId = $request->marketing_import_id;
            } else {
                $marketingImport = new MarketingImport();
                $marketingImport->contact_id = $request->customer;
                $marketingImport->status = 1;
                $marketingImport->save();

                $marketingImportId = $marketingImport->id;
            }

            $salesValue = str_replace(',', '', $request->total_all);

            $quotation = new QuotationMarketingImport();
            $quotation->marketing_import_id = $marketingImportId;
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
                    $group = GroupQuotationMIm::create([
                        'quotation_m_im_id' => $quotation->id,
                        'group' => $groupKey,
                    ]);
            
                    // Insert items group quotation to database
                    for ($i = 0; $i < count($request->$descriptionKey); $i++) {
                        $item = ItemGroupQuotationMIm::create([
                            'group_quotation_m_im_id' => $group->id,
                            'description' => $request->$descriptionKey[$i],
                            'total' => str_replace(',', '', $request->$totalKey[$i]),
                            'remark' => $request->$remarkKey[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('marketing.import.index');
        } 
        catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->route('marketing.import.create-quotation');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MarketingImport::with('documents', 'dimensions')->findOrFail($id);

        return view('marketing::marketing-import.show', compact('data'));
    }

    public function editQuotation($id)
    {
        $marketingImport = MarketingImport::with('quotation')->findOrFail($id);
        $quotation = QuotationMarketingImport::where('marketing_import_id', $id)->first();
        $group = GroupQuotationMIm::where('quotation_m_im_id', $quotation->id)->with('items')->get();
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();

        // convert to json
        $json_group = json_encode($group, true);

        return view('marketing::marketing-import.edit-quotation', compact('marketingImport', 'quotation', 'group', 'json_group', 'contact', 'terms', 'currencies'));
    }

    public function showQuotation($id)
    {
        $quotation = QuotationMarketingImport::where('marketing_import_id', $id)->firstOrFail();
        $marketingImport = MarketingImport::find($id);
        $group = GroupQuotationMIm::where('quotation_m_im_id', $quotation->id)->get();
        $totalGroup = $group->count();
        return view('marketing::marketing-import.show-quotation', compact('quotation', 'marketingImport', 'group', 'totalGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {        
        $marketingImport = MarketingImport::with('quotation', 'dimensions')->findOrFail($id);
        $contact = MasterContact::all();
        $terms = MasterTermOfPayment::all();

        return view('marketing::marketing-import.edit', compact('marketingImport', 'contact', 'terms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarketingRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            //update marketing to Database
            $existing_document_marketing = DocumentMarketingImport::where('marketing_import_id', $id)->get();
            $document_marketing = $request->file('documents') ?? [];

            if(count($existing_document_marketing) + count($document_marketing) > 3) {
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents']);
            }

            $data = marketingImport::find($id);
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
            $dimension = DimensionMarketingImport::where('marketing_import_id', $id);
            $dimension->forceDelete();

            //create new data dimension
            if ($request->panjang) {
                if ($request->panjang[0] != null) {
                    for($i = 0; $i < count($request->panjang); $i++){
                        $dimension = DimensionMarketingImport::create([
                            'marketing_import_id' => $id,
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
                    $dataDocument['marketing_import_id'] = $data->id;
                    $dataDocument['document'] = $file->storeAs(
                        'marketing-import/documents',
                        $name,
                        'public'
                    );
                    DocumentMarketingImport::create($dataDocument);
                }
            }

            //create operation if status was selling
            if ($request->status == 2) {
                //cek dulu di operation apakah data marketing sudah masuk kesitu, jika blm maka buat, kalau jika sudah maka jgn lakukan apa2
                $checkOperation = OperationImport::where('marketing_import_id', $data->id)->first();

                if ($checkOperation) {
                    //do nothing
                } else {
                    $operation = new OperationImport();
                    $operation->marketing_import_id = $data->id;
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
                $operation = OperationImport::where('marketing_import_id', $data->id);
                $operation->forceDelete();
            }

            DB::commit();
            toast('Data Updated Successfully!','success');
            return redirect()->route('marketing.import.index');
        } catch (\Exception $e) {
            toast('Data Update Failed!','error');
            return redirect()->back();
        }
    }

    public function updateQuotation(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $quotation = QuotationMarketingImport::find($request->quotation_id);
            $quotation->date = $request->date;
            $quotation->quotation_no = $request->quotation_no;
            $quotation->valid_until = $request->valid_until;
            $quotation->project_desc = $request->project_desc;

            $currencyId = MasterCurrency::where('initial', $request->currency)->first()->id;
            $quotation->currency_id = $currencyId;
            $quotation->save();


            //update sales
            //update total sales value to quotation
            $quotationb = QuotationMarketingImport::find($request->quotation_id);
            $salesValue = str_replace(',', '', $request->total_all);
            $quotationb->sales_value = $salesValue;
            $quotationb->save();

            //delete data before
            $groupSales = GroupQuotationMIm::where('quotation_m_im_id', $request->quotation_id);

            $groupSales->forceDelete();

            $itemSales = ItemGroupQuotationMIm::where('group_quotation_m_im_id', null);
            $itemSales->forceDelete();

            $alphabet = $request->alphabet;
            $groups = range('A', $alphabet); // Definisikan grup-grup yang mungkin ada

            foreach ($groups as $groupKey) {
                $descriptionKey = "description_$groupKey";
                $totalKey = "total_$groupKey";
                $remarkKey = "remark_$groupKey";

                if ($request->$descriptionKey) {
                    $group = GroupQuotationMIm::create([
                        'quotation_m_im_id' => $quotation->id,
                        'group' => $groupKey,
                    ]);

                    // Insert items group quotation to database
                    for ($i = 0; $i < count($request->$descriptionKey); $i++) {
                        $item = ItemGroupQuotationMIm::create([
                            'group_quotation_m_im_id' => $group->id,
                            'description' => $request->$descriptionKey[$i],
                            'total' => str_replace(',', '', $request->$totalKey[$i]),
                            'remark' => $request->$remarkKey[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            toast('Data Updated Successfully!','success');
            return redirect()->route('marketing.import.index');
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
        $marketingImport = MarketingImport::find($id);
        //make  job order id null
        $data = MarketingImport::find($id);
        $data->job_order_id = null;
        $data->save();

        $marketingImport->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->route('marketing.import.index');
    }

    public function deleteDocument(Request $request)
    {
        $document = DocumentMarketingImport::find($request->delete_document_id);
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
            $quotation = QuotationMarketingImport::find($request->quotation_id);
            $quotation->forceDelete();

            $groupSales = GroupQuotationMIm::where('quotation_m_im_id', $request->quotation_id);
            $groupSales->forceDelete();

            $itemSales = ItemGroupQuotationMIm::where('group_quotation_m_im_id', null);
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
