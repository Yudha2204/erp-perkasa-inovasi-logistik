<?php

namespace Modules\Operation\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Operation\App\Models\ActivityOperationImport;
use Modules\Operation\App\Models\DocumentActivityOpIm;
use Modules\Operation\App\Models\DocumentArrivalOpIm;
use Modules\Operation\App\Models\DocumentProgressOpIm;
use Modules\Operation\App\Models\OperationImport;
use Modules\Operation\App\Models\ProgressOperationImport;
use Modules\Operation\App\Models\VendorOperationImport;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\App\Models\NotificationCustom;

class OperationImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-import@operation', ['only' => ['index','show']]);
        $this->middleware('permission:edit-import@operation', ['only' => ['create','createProgress','store','storeProgress']]);
        $this->middleware('permission:edit-import@operation', ['only' => ['edit','editProgress','update','updateProgress']]);
        $this->middleware('permission:edit-import@operation', ['only' => ['destroy','deleteProgress','deleteProgressDocument']]);
    }

    //Fungsi Search
    public function searchIndex($search){
        $index = OperationImport::query();

        //Jika input search terisi
        if($search) {
            $index->whereHas('marketing', function ($query) use ($search){
                $query->where('job_order_id', 'like', '%'.$search.'%');
            })
            ->orWhere('departure_date','like',"%".$search."%")
            ->orWhere('arrival_date','like',"%".$search."%")
            ->orWhere('origin','like',"%".$search."%")
            ->orWhere('destination','like',"%".$search."%")
            ->orWhere('recepient_name','like',"%".$search."%");

            if (str_contains("ON - PROGRESS", strtoupper($search), )) {
                $index->orWhere('status', 1);
            } 
            if (str_contains("END - PROGRESS", strtoupper($search), )) {
                $index->orWhere('status', 2);
            }
        }
    
        return $index->paginate(10);
    }
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $operationImports = $this->searchIndex($search); //Memanggil fungsi search
        } else {
            $operationImports = OperationImport::orderBy('id', 'DESC')->paginate(10);
        }
        return view('operation::operation-import.index', compact('operationImports', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operation::operation-import.create');
    }

    public function createProgress(Request $request)
    {
        $operationImport = OperationImport::find($request->id);

        $data = ProgressOperationImport::with('documents')->where('operation_import_id', $operationImport->id)->get() ?? [];
        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */

     public function generateUniqueCode()
     {
         $count = OperationImport::where('job_order_id', '!=', null)->count();
         //cek apakah sudah ada data?
         if ($count > 0) {
             $last_data = OperationImport::where('job_order_id', '!=', null)->orderBy('job_order_id', 'desc')->first()->job_order_id;
             $removed4char = substr($last_data, -5);
             $generate_code = 'OPIL' . '-' .  str_pad($removed4char + 1, 5, "0", STR_PAD_LEFT);
         } else {
             $generate_code = 'OPIL' . '-' . str_pad(1, 5, "0", STR_PAD_LEFT);
         }
 
         return $generate_code;
     }


    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            //Insert data operation to Database
            
            $data = new OperationImport();
            $data->job_order_id = $this->generateUniqueCode();
            $data->marketing_import_id = null;
            $data->origin = $request->origin;
            $data->pickup_address = $request->pickup_address;
            $data->pickup_address_desc = $request->pickup_address_desc;
            $data->pickup_date = $request->pickup_date;
            $data->transportation = $request->transportation;
            $data->transportation_desc = $request->transportation_desc;
            $data->departure_date = $request->departure_date;
            $data->departure_time = $request->departure_time;
            //arrival
            $data->destination = $request->destination;
            $data->arrival_date = $request->arrival_date;
            $data->arrival_time = $request->arrival_time;
            $data->delivery_address = $request->delivery_address;
            $data->delivery_address_desc = $request->delivery_address_desc;
            $data->recepient_name = $request->recepient_name;
            $data->arrival_desc = $request->arrival_desc;
            $data->remark = $request->remark;
            $data->status = $request->status;
            $existing_document_operation = DocumentArrivalOpIm::where('operation_import_id', $data->id)->get();
            $document_operation = $request->file('documents') ?? [];

            if(count($existing_document_operation) + count($document_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }
            $data->save();

            //store activity to database
            $activity = new ActivityOperationImport();
            $activity->operation_import_id = $data->id;
            $activity->batam_entry_date = $request->batam_entry_date;
            $activity->batam_exit_date = $request->batam_exit_date;
            $activity->destination_entry_date = $request->destination_entry_date;
            $activity->warehouse_entry_date = $request->warehouse_entry_date;
            $activity->warehouse_exit_date = $request->warehouse_exit_date;
            $activity->client_received_date = $request->client_received_date;
            $activity->sin_entry_date = $request->sin_entry_date;
            $activity->sin_exit_date = $request->sin_exit_date;
            $activity->return_pod_date = $request->return_pod_date;
            $existing_document_ac_operation = DocumentActivityOpIm::where('activity_operation_import_id', $activity->id)->get();
            $document_ac_operation = $request->file('document_activities') ?? [];

            if(count($existing_document_ac_operation) + count($document_ac_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }
            $activity->save();

            //store vendor to databse
            if($request->transit) {
                foreach($request->transit as $transit) {
                    $vendor = new VendorOperationImport();
                    $vendor->operation_import_id = $data->id;
                    $vendor->vendor = null;
                    $vendor->total_charge = null;
                    $vendor->transit = $transit;
                    $vendor->save();
                }
            }

            //insert document activity
            if ($files = $request->file('document_activities')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocument['activity_operation_import_id'] = $activity->id;
                    $dataDocument['document'] = $file->storeAs(
                        'operation-import/activity/documents',
                        $name,
                        'public'
                    );
                    DocumentActivityOpIm::create($dataDocument);
                }
            }

            //insert document operation arrival
            if ($files = $request->file('documents')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocuments['operation_import_id'] = $data->id;
                    $dataDocuments['document'] = $file->storeAs(
                        'operation-import/documents',
                        $name,
                        'public'
                    );
                    DocumentArrivalOpIm::create($dataDocuments);
                }
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('operation.import.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->back()->withInput();
        }
    }

    public function storeProgress(Request $request): RedirectResponse
    {
        //validate store
        $validator = Validator::make($request->all(), [
            'date_progress'     => 'required',
            'location'   => 'required',
            'transportation'   => 'required'
        ]);

        if ($validator->fails()) {
            toast('failed to add data!','error');
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            //Insert data operation to Database
            
            $data = new ProgressOperationImport();
            $data->operation_import_id = $request->operation_import_id;
            $data->date_progress = $request->date_progress;
            $data->time_progress = $request->time_progress;
            $data->location = $request->location;
            $data->location_desc = $request->location_desc;
            $data->transportation = $request->transportation;
            $data->transportation_desc = $request->transportation_desc;
            $data->carrier = $request->carrier;
            $data->description = $request->description;
            $data->save();

            $existing_document_pro_operation = DocumentProgressOpIm::where('progress_operation_import_id', $data->id)->get();
            $document_pro_operation = $request->file('documents') ?? [];

            if(count($existing_document_pro_operation) + count($document_pro_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }

            //insert document operation arrival
            if ($files = $request->file('documents')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocuments['progress_operation_import_id'] = $data->id;
                    $dataDocuments['document'] = $file->storeAs(
                        'operation-import/progress/documents',
                        $name,
                        'public'
                    );
                    DocumentProgressOpIm::create($dataDocuments);
                }
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('operation.import.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $operationImport = OperationImport::findOrFail($id);

        $activity = ActivityOperationImport::where('operation_import_id', $id)->first();

        if ($activity) {
            $documentActivity = DocumentActivityOpIm::where('activity_operation_import_id', $activity->id)->get();
            if(count($documentActivity) < 1) {
                $documentActivity = null;
            }
        } else {
            $documentActivity = null;
        }

        $documentArrival = DocumentArrivalOpIm::where('operation_import_id', $id)->first();

        if ($documentArrival) {
            $documentArrival = DocumentArrivalOpIm::where('operation_import_id', $id)->get();
        } else {
            $documentArrival = null;
        }

        $vendor = VendorOperationImport::where('operation_import_id', $id)->get();

        $progress = ProgressOperationImport::where('operation_import_id', $id)->get() ?? [];
        
        return view('operation::operation-import.show', compact('operationImport', 'activity', 'documentActivity', 'documentArrival', 'vendor', 'progress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $operationImport = OperationImport::findOrFail($id);

        $activity = ActivityOperationImport::where('operation_import_id', $id)->first();

        if ($activity) {
            $documentActivity = DocumentActivityOpIm::where('activity_operation_import_id', $activity->id)->get();
            if(count($documentActivity) < 1) {
                $documentActivity = null;
            }
        } else {
            $documentActivity = null;
        }

        $documentArrival = DocumentArrivalOpIm::where('operation_import_id', $id)->first();

        if ($documentArrival) {
            $documentArrival = DocumentArrivalOpIm::where('operation_import_id', $id)->get();
        } else {
            $documentArrival = null;
        }

        $vendor = VendorOperationImport::where('operation_import_id', $id)->get();
        
        return view('operation::operation-import.edit', compact('operationImport', 'activity', 'documentActivity', 'documentArrival', 'vendor'));
    }

    public function editProgress(Request $request)
    {
        $data = ProgressOperationImport::find($request->id);
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
        DB::beginTransaction();
        try {
            //update data operation to Database
            
            $data = OperationImport::find($id);
            $data->origin = $request->origin;
            $data->pickup_address = $request->pickup_address;
            $data->pickup_address_desc = $request->pickup_address_desc;
            $data->pickup_date = $request->pickup_date;
            $data->transportation = $request->transportation;
            if ($request->transportation == 3) {
                $data->transportation_desc = null;
            } else {
                $data->transportation_desc = $request->transportation_desc;
            }
            $data->departure_date = $request->departure_date;
            $data->departure_time = $request->departure_time;
            //arrival
            $data->destination = $request->destination;
            $data->arrival_date = $request->arrival_date;
            $data->arrival_time = $request->arrival_time;
            $data->delivery_address = $request->delivery_address;
            $data->delivery_address_desc = $request->delivery_address_desc;
            $data->recepient_name = $request->recepient_name;
            $data->arrival_desc = $request->arrival_desc;
            $data->remark = $request->remark;
            $data->status = $request->status;
            $existing_document_operation = DocumentArrivalOpIm::where('operation_import_id', $data->id)->get();
            $document_operation = $request->file('documents') ?? [];

            if(count($existing_document_operation) + count($document_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }
            $data->save();

            //update activity to database

            //cek if activity id exist
            if ($request->activity_id) {
                $activity = ActivityOperationImport::find($request->activity_id);
            } else {
                $activity = new ActivityOperationImport();
                $activity->operation_import_id = $id;
            }

            $activity->batam_entry_date = $request->batam_entry_date;
            $activity->batam_exit_date = $request->batam_exit_date;
            $activity->destination_entry_date = $request->destination_entry_date;
            $activity->warehouse_entry_date = $request->warehouse_entry_date;
            $activity->warehouse_exit_date = $request->warehouse_exit_date;
            $activity->client_received_date = $request->client_received_date;
            $activity->sin_entry_date = $request->sin_entry_date;
            $activity->sin_exit_date = $request->sin_exit_date;
            $activity->return_pod_date = $request->return_pod_date;
            $existing_document_ac_operation = DocumentActivityOpIm::where('activity_operation_import_id', $activity->id)->get();
            $document_ac_operation = $request->file('document_activities') ?? [];

            if(count($existing_document_ac_operation) + count($document_ac_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }
            $activity->save();


            $errors = [];
            //update vendor to databse
            if($request->transit) {
                foreach($request->transit as $idx => $transit) {
                    if ($request->vendor_id && isset($request->vendor_id[$idx])) {
                        $vendor = VendorOperationImport::find($request->vendor_id[$idx]);
                    } else {
                        $vendor = new VendorOperationImport();
                        $vendor->operation_import_id = $id;
                    }
    
                    if($vendor->vendor !== null) {
                        $errors[] = $vendor->transit;
                        continue;
                    }
    
                    $operator = $request->operator[$idx];
                    $exp_operator = explode(":", $operator);
                    if($exp_operator[1] === "delete") {
                        VendorOperationImport::find($exp_operator[0])->delete();
                        continue;
                    }
                    
                    $vendor->transit = $transit;
                    $vendor->save();
                }
            }

            if(count($errors) > 0) {
                $imp = implode(", ", $errors);
                toast('Data Added Failed!','error');
                return redirect()->back()->withInput()->withErrors(["error" => "There is vendor of $imp transits"]);
            }

            //insert document activity
            if ($files = $request->file('document_activities')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocument['activity_operation_import_id'] = $activity->id;
                    $dataDocument['document'] = $file->storeAs(
                        'operation-import/activity/documents',
                        $name,
                        'public'
                    );
                    DocumentActivityOpIm::create($dataDocument);

                    NotificationCustom::create([
                        "group_name" => "finance",
                        "date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "type" => "info-document",
                        "content" => "Dokumen POD balik $name sudah diterima"
                    ]);
                }
            }

            //insert document operation arrival
            if ($files = $request->file('documents')) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocuments['operation_import_id'] = $data->id;
                    $dataDocuments['document'] = $file->storeAs(
                        'operation-import/documents',
                        $name,
                        'public'
                    );
                    DocumentArrivalOpIm::create($dataDocuments);
                }
            }

            DB::commit();
            toast('Data Added Successfully!','success');
            return redirect()->route('operation.import.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!','error');
            return redirect()->back()->withInput();
        }   
    }

    public function updateProgress(Request $request)
    {
        DB::beginTransaction();
        try {
        foreach ($request->id_progress as $id_progress) {
            //update data operation to Database
            $data = ProgressOperationImport::find($id_progress);
            $data->date_progress = $request->e_date_progress[$id_progress];
            $data->time_progress = $request->e_time_progress[$id_progress];
            $data->location = $request->e_location[$id_progress];
            $data->location_desc = $request->e_location_desc[$id_progress];
            $data->transportation = $request->e_transportation[$id_progress];
            $data->transportation_desc = $request->e_transportation_desc[$id_progress] ?? null;
            $data->carrier = $request->e_carrier[$id_progress];
            $data->description = $request->e_description[$id_progress];
            $data->update();

            $existing_document_pro_operation = DocumentProgressOpIm::where('progress_operation_import_id', $data->id)->get();
            $document_pro_operation = $request->file('documents') ?? [];

            if(count($existing_document_pro_operation) + count($document_pro_operation) > 3) {
                DB::rollBack();
                return redirect()->back()->withErrors(["document" => 'Only accept 3 file of documents'])->withInput();
            }

            if ($request->e_document && $request->e_documents[$id_progress]) {
                foreach ($request->e_documents[$id_progress] as $file) {
                    $name = $file->getClientOriginalName();
                    $dataDocuments['progress_operation_import_id'] = $data->id;
                    $dataDocuments['document'] = $file->storeAs(
                        'operation-import/progress/documents',
                        $name,
                        'public'
                    );
                    DocumentProgressOpIm::create($dataDocuments);
                }
            }

        }

        DB::commit();
        toast('Data Updated Successfully!', 'success');
        return redirect()->route('operation.import.index');
        } catch (\Exception $e) {
            toast('Data Added Failed!', 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $operationImport = OperationImport::find($id);
        //make  job order id null
        $data = OperationImport::find($id);
        $data->job_order_id = null;
        $data->save();

        $operationImport->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }

    public function deleteProgress($id)
    {
        $progress = ProgressOperationImport::find($id);

        $progress->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }

    public function deleteProgressDocument($id)
    {
        $document = DocumentProgressOpIm::find($id);
        $document->delete();

        toast('Data Deleted Successfully!','success');
        return [
            "message" => "Success"
        ];
    }

    public function deleteActivityDocument(Request $request)
    {
        $document = DocumentActivityOpIm::find($request->delete_document_activity_id);
        $document->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }

    public function deleteArrivalDocument(Request $request)
    {
        $document = DocumentArrivalOpIm::find($request->delete_document_arrival_id);
        $document->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
