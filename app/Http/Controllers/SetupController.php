<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    /**
     * Display the setup page.
     */
    public function index()
    {
        $setup = Setup::first();
        return view('setup.index', compact('setup'));
    }

    /**
     * Show the form for creating a new setup.
     */
    public function create()
    {
        return view('setup.create');
    }

    /**
     * Store a newly created setup in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_entry_period' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('public/logos', $logoName);
            $data['company_logo'] = 'logos/' . $logoName;
        }

        Setup::create($data);
        
        // Clear cache after creating setup
        Setup::clearStartEntryPeriodCache();

        return redirect()->route('setup.index')
            ->with('success', 'Setup created successfully!');
    }

    /**
     * Display the specified setup.
     */
    public function show(Setup $setup)
    {
        return view('setup.show', compact('setup'));
    }

    /**
     * Show the form for editing the specified setup.
     */
    public function edit(Setup $setup)
    {
        return view('setup.edit', compact('setup'));
    }

    /**
     * Update the specified setup in storage.
     */
    public function update(Request $request, Setup $setup): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_entry_period' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($setup->company_logo && Storage::exists('public/' . $setup->company_logo)) {
                Storage::delete('public/' . $setup->company_logo);
            }

            $logo = $request->file('company_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('public/logos', $logoName);
            $data['company_logo'] = 'logos/' . $logoName;
        } else {
            // Keep existing logo if no new one uploaded
            unset($data['company_logo']);
        }

        $setup->update($data);
        
        // Clear cache after updating setup
        Setup::clearStartEntryPeriodCache();

        return redirect()->route('setup.index')
            ->with('success', 'Setup updated successfully!');
    }

    /**
     * Remove the specified setup from storage.
     */
    public function destroy(Setup $setup): RedirectResponse
    {
        // Delete logo file if exists
        if ($setup->company_logo && Storage::exists('public/' . $setup->company_logo)) {
            Storage::delete('public/' . $setup->company_logo);
        }

        $setup->delete();

        return redirect()->route('setup.index')
            ->with('success', 'Setup deleted successfully!');
    }
}
