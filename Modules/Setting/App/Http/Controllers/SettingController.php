<?php

namespace Modules\Setting\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Setting\App\Models\LogoAddress;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = LogoAddress::first();
        if(!$photos) {
            $photos = null;
        };
        return view('setting::index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting::create');
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
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = 1): RedirectResponse
    {
        // Ambil data awal dari database
        $dataAwal = LogoAddress::first();
        if($dataAwal){
            $dataAwal = LogoAddress::first();
        }else{
            $dataAwal = LogoAddress::find($id);
        }
     
        if($request->address){
            $address = $request->address;
        }else{
            $address = $dataAwal->address;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        
        } elseif ($dataAwal) {
            $imagePath = $dataAwal->image_path;
        } else {
            $imagePath = "";
        }
        
        if($dataAwal){
            toast('success update data','success');
            LogoAddress::updateOrCreate(
                ['id' => $dataAwal->id], // Tentukan ID yang akan diupdate
                [
                    'address' => $address,
                    'image_path' => $imagePath,
                ]
            );
        }else{
            toast('success update data','success');
            LogoAddress::updateOrCreate(
                ['id' => $id], // Tentukan ID yang akan diupdate
                [
                    'address' => $address,
                    'image_path' => $imagePath,
                ]
            );
        }

        // Redirect kembali ke halaman index settings dengan pesan sukses
        return redirect()->route('setting.index')->with('success', 'Photo and address updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
