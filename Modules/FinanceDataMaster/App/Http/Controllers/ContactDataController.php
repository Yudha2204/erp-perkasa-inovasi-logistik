<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinanceDataMaster\App\Models\TermPaymentContact;
use Illuminate\Support\Facades\Validator;

class ContactDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-contact@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-contact@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-contact@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-contact@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = MasterContact::query();

        if($search) {
            $index->where('customer_id','like',"%".$search."%")
                ->orWhere('customer_name','like',"%".$search."%")
                ->orWhere('email','like',"%".$search."%")
                ->orWhere('phone_number','like',"%".$search."%");

            if (str_contains("CUSTOMER", strtoupper($search), )) {
                $index->orWhereJsonContains('type', "1");
            } 
            if (str_contains("VENDOR", strtoupper($search), )) {
                $index->orWhereJsonContains('type', "2");
            } 
            if (str_contains("KARYAWAN", strtoupper($search), )) {
                $index->orWhereJsonContains('type', "3");
            } 
            if (str_contains("SUPPLIER", strtoupper($search), )) {
                $index->orWhereJsonContains('type', "4");
            } 
        }

        return $index->paginate(10);
    }
    

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $contact = $this->searchFilterIndex($search);
        } else {
            $contact = MasterContact::orderBy('id', 'DESC')->paginate(10);
        }

        $terms = MasterTermOfPayment::all();
        return view('financedatamaster::contact.index', compact('contact', 'terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    public function generateCode($name)
    {
        $nameArray = explode(' ', $name);
        $code = '';

        foreach ($nameArray as $word) {
            $code .= strtoupper(substr($word, 0, 1));
        }

        // Finding the number of employees with the same code
        $count = MasterContact::count();

        // Formatting the code with an incremented number
        $formattedCode = $code . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return $formattedCode;
    }

    public function generateCodeEdit($name, $customerId)
    {
        $nameArray = explode(' ', $name);
        $code = '';

        foreach ($nameArray as $word) {
            $code .= strtoupper(substr($word, 0, 1));
        }

        $number = substr($customerId, -3);

        // Formatting the code with an incremented number
        $formattedCode = $code . '-' . $number;
        return $formattedCode;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->id) {
            //validate store
            $validator = Validator::make($request->all(), [
                'customer_name'   => 'required',
                'contact_type'    => 'required'
            ]);

            if ($validator->fails()) {
                toast('failed to add data!','error');
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            DB::beginTransaction();
            try {
                $data = new MasterContact();
                $name = $request->customer_name;
                $data->customer_id = $this->generateCode($name);
                $data->customer_name = $request->customer_name;
                $data->title = $request->title;
                $data->phone_number = $request->phone_number;
                $data->email = $request->email;
                $data->npwp_ktp = $request->npwp_ktp;
                $data->type = json_encode($request->contact_type);
                $data->company_name = $request->company_name;
                $data->type_of_company = $request->type_of_company;
                $data->company_tax_status = $request->company_tax_status;
                $data->bank_branch = $request->bank_branch;
                $data->acc_name = $request->acc_name;
                $data->acc_no = $request->acc_no;
                $data->swift_code = $request->swift_code;
                $data->address = $request->address;
                $data->city = $request->city;
                $data->postal_code = $request->postal_code;
                $data->country = $request->country;
                $data->pic_for_urgent_status = $request->pic_for_urgent_status;
                $data->mobile_number = $request->mobile_number;
    
                //insert documents
                if ($request->file('document')) {
                    $name = $request->file('document')->getClientOriginalName();
                    $file = $request->file('document')->storeAs('master-data/contact/document', $name, 'public');
                    $data->document = $file;
                }
                $data->save();
    
                //insert term
                if ($request->term_payment_id) {
                    for($i = 0; $i < count($request->term_payment_id); $i++){
                        $term = TermPaymentContact::create([
                            'term_payment_id' => $request->term_payment_id[$i],
                            'contact_id' => $data->id,
                        ]);
                    }
                }
    
                DB::commit();
                toast('Data Added Successfully!','success');
                return redirect()->back();
            } catch (\Exception $e) {
                toast('Data Added Failed!','error');
                return redirect()->back();
            }

        } else {
            //validate edit
            $validator = Validator::make($request->all(), [
                'customer_name'   => 'required',
                'contact_type'    => 'required'
            ]);

            if ($validator->fails()) {
                toast('failed to add data!','error');
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            DB::beginTransaction();
            try {
                $data = MasterContact::find($request->id);
                //ganti customer id jika customer name berbeda dari sebelumnya
                if ($data->customer_name != $request->customer_name) {
                    $name = $request->customer_name;
                    $customerId = $data->customer_id;
                    $data->customer_id = $this->generateCodeEdit($name, $customerId);
                }
                $data->customer_name = $request->customer_name;
                $data->title = $request->title;
                $data->phone_number = $request->phone_number;
                $data->email = $request->email;
                $data->npwp_ktp = $request->npwp_ktp;
                $data->type = json_encode($request->contact_type);
                $data->company_name = $request->company_name;
                $data->type_of_company = $request->type_of_company;
                $data->company_tax_status = $request->company_tax_status;
                $data->bank_branch = $request->bank_branch;
                $data->acc_name = $request->acc_name;
                $data->acc_no = $request->acc_no;
                $data->swift_code = $request->swift_code;
                $data->address = $request->address;
                $data->city = $request->city;
                $data->postal_code = $request->postal_code;
                $data->country = $request->country;
                $data->pic_for_urgent_status = $request->pic_for_urgent_status;
                $data->mobile_number = $request->mobile_number;
    
                //insert documents
                if ($request->file('document')) {
                    if ($data->document && file_exists(storage_path('app/public/' . $data->document))) {
                        unlink(storage_path('app/public/'.$data->document));
                    }
                    $name = $request->file('document')->getClientOriginalName();
                    $file = $request->file('document')->storeAs('master-data/contact/document', $name, 'public');
                    $data->document = $file;
                }
                $data->save();

                //insert term
                if ($request->term_payment_id) {
                    //delete data old
                    $deleteTerm = TermPaymentContact::where('contact_id', $data->id)->delete();

                    for($i = 0; $i < count($request->term_payment_id); $i++){
                        $term = TermPaymentContact::create([
                            'term_payment_id' => $request->term_payment_id[$i],
                            'contact_id' => $data->id,
                        ]);
                    }
                }
    
                DB::commit();
                toast('Data Updated Successfully!','success');
                return redirect()->back();
            } catch (\Exception $e) {
                toast('Data Update Failed!','error');
                return redirect()->back();
            }
        }
    }

    public function edit($id)
    {
        $data = MasterContact::with('termPaymentContacts')->find($id);
        $data['master_term_of_payment'] = MasterTermOfPayment::all();

        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MasterContact::with('termPaymentContacts')->find($id);
        $data['master_term_of_payment'] = MasterTermOfPayment::all();

        return response()->json([
            'success' => true,
            'data'    => $data
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = MasterContact::find($id);
            $data->customer_id = $request->customer_id;
            $data->customer_name = $request->customer_name;
            $data->title = $request->title;
            $data->phone_number = $request->phone_number;
            $data->email = $request->email;
            $data->npwp_ktp = $request->npwp_ktp;
            
            $data->type = json_encode($request->contact_type);
            $data->company_name = $request->company_name;
            $data->type_of_company = $request->type_of_company;
            $data->company_tax_status = $request->company_tax_status;
            $data->company_business_converage = $request->company_business_converage;
            $data->year_of_build = $request->year_of_build;
            $data->term_of_payment = $request->term_of_payment;
            $data->payment_method = $request->payment_method;
            $data->bank_branch = $request->bank_branch;
            $data->acc_name = $request->acc_name;
            $data->acc_no = $request->acc_no;
            $data->swift_code = $request->swift_code;
            $data->address = $request->address;
            $data->city = $request->city;
            $data->postal_code = $request->postal_code;
            $data->country = $request->country;
            $data->pic_for_urgent_status = $request->pic_for_urgent_status;
            $data->mobile_number = $request->mobile_number;

            //insert documents
            if ($request->file('document')) {
                if ($data->document && file_exists(storage_path('app/public/' . $data->document))) {
                    unlink(storage_path('app/public/'.$data->document));
                }
                $name = $request->file('document')->getClientOriginalName();
                $file = $request->file('document')->storeAs('master-data/document', $name, 'public');
                $data->document = $file;
            }

            $data->save();


            DB::commit();
            toast('Data Updated Successfully!','success');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Data Update Failed!','error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = MasterContact::find($id);
        if(!empty($contact->document)) {
            if (file_exists(storage_path('app/public/' . $contact->document))) {
                unlink(storage_path('app/public/'.$contact->document));
            }
        }
        $contact->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
