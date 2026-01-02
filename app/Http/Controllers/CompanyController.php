<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255|unique:companies',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url',
            'gst_number' => 'nullable|string|max:15',
            'company_description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:11',
            'account_type' => 'nullable|string|max:50',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'web_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'mail_icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = 'storage/' . $request->file('logo_path')->store('company-logos', 'public');
        }
        if ($request->hasFile('qr_code_path')) {
            $validated['qr_code_path'] = 'storage/' . $request->file('qr_code_path')->store('company-qrcodes', 'public');
        }
        if ($request->hasFile('web_logo_path')) {
            $validated['web_logo_path'] = 'storage/' . $request->file('web_logo_path')->store('company-logos', 'public');
        }
        if ($request->hasFile('phone_icon_path')) {
            $validated['phone_icon_path'] = 'storage/' . $request->file('phone_icon_path')->store('company-icons', 'public');
        }
        if ($request->hasFile('mail_icon_path')) {
            $validated['mail_icon_path'] = 'storage/' . $request->file('mail_icon_path')->store('company-icons', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255|unique:companies,company_name,' . $company->id,
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url',
            'gst_number' => 'nullable|string|max:15',
            'company_description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:11',
            'account_type' => 'nullable|string|max:50',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'web_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'mail_icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle file uploads - keep existing if not updated
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = 'storage/' . $request->file('logo_path')->store('company-logos', 'public');
        }
        if ($request->hasFile('qr_code_path')) {
            $validated['qr_code_path'] = 'storage/' . $request->file('qr_code_path')->store('company-qrcodes', 'public');
        }
        if ($request->hasFile('web_logo_path')) {
            $validated['web_logo_path'] = 'storage/' . $request->file('web_logo_path')->store('company-logos', 'public');
        }
        if ($request->hasFile('phone_icon_path')) {
            $validated['phone_icon_path'] = 'storage/' . $request->file('phone_icon_path')->store('company-icons', 'public');
        }
        if ($request->hasFile('mail_icon_path')) {
            $validated['mail_icon_path'] = 'storage/' . $request->file('mail_icon_path')->store('company-icons', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}
