<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerImporter;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|min:2|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:6',
            'gst_no' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
            'gst_type' => 'required|in:instate,outofstate',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email:rfc,dns|max:255',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.min' => 'Customer name must be at least 2 characters.',
            'customer_name.max' => 'Customer name cannot exceed 255 characters.',
            'gst_no.regex' => 'Invalid GST number format. Expected format: 27AABCU9603R1Z5',
            'gst_type.required' => 'Please select a GST type.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.regex' => 'Mobile number must be exactly 10 digits.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $customer = Customer::create($validated);

        // Return JSON if AJAX request, otherwise redirect
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($customer->only(['id', 'customer_name', 'address', 'city', 'state', 'mobile', 'gst_no']), 201);
        }

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|min:2|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:6',
            'gst_no' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
            'gst_type' => 'required|in:instate,outofstate',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email:rfc,dns|max:255',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.min' => 'Customer name must be at least 2 characters.',
            'customer_name.max' => 'Customer name cannot exceed 255 characters.',
            'gst_no.regex' => 'Invalid GST number format. Expected format: 27AABCU9603R1Z5',
            'gst_type.required' => 'Please select a GST type.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.regex' => 'Mobile number must be exactly 10 digits.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    /**
     * Show the XLSX import form
     */
    public function importForm()
    {
        return view('customers.import');
    }

    /**
     * Handle XLSX file import for customers
     */
    public function import(Request $request)
    {
        // Validate file
        $request->validate([
            'xlsx_file' => 'required|file|mimes:xlsx,xls|max:5120'
        ], [
            'xlsx_file.required' => 'Please select an XLSX file to import.',
            'xlsx_file.mimes' => 'File must be an XLSX or XLS file.',
            'xlsx_file.max' => 'File size cannot exceed 5MB.'
        ]);

        try {
            $filePath = $request->file('xlsx_file')->getRealPath();
            $importer = new CustomerImporter();
            $result = $importer->import($filePath);

            // Build response message
            $message = $result['message'];

            if ($result['success']) {
                return back()
                    ->with('success', $message)
                    ->with('import_stats', [
                        'imported' => $result['imported'],
                        'skipped' => $result['skipped'],
                    ]);
            } else {
                return back()
                    ->with('error', '⚠️ Import completed with errors. Please review:')
                    ->with('errors', $result['errors'])
                    ->with('import_stats', [
                        'imported' => $result['imported'],
                        'skipped' => $result['skipped'],
                    ]);
            }

        } catch (\Exception $e) {
            return back()
                ->with('error', '❌ XLSX Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download the CSV template for customer imports
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer_import_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'Customer Name',
            'Address',
            'City',
            'State',
            'Pin Code',
            'GST No',
            'GST Type (instate/outofstate)',
            'Mobile',
            'Email'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Add a sample row
            fputcsv($file, [
                'Acme Corp',
                '123 Business Rd',
                'Mumbai',
                'Maharashtra',
                '400001',
                '27AABCU9603R1Z5',
                'instate',
                '9876543210',
                'contact@acme.com'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all customers to a CSV file
     */
    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_export.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'Customer Name',
            'Address',
            'City',
            'State',
            'Pin Code',
            'GST No',
            'GST Type',
            'Mobile',
            'Email',
            'Created At'
        ];

        $customers = Customer::all();

        $callback = function () use ($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_name,
                    $customer->address,
                    $customer->city,
                    $customer->state,
                    $customer->pin_code,
                    $customer->gst_no,
                    $customer->gst_type == 'instate' ? 'In-State' : 'Out-of-State',
                    $customer->mobile,
                    $customer->email,
                    $customer->created_at ? $customer->created_at->format('Y-m-d') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function trash()
    {
        // Show only deleted customers
        $customers = Customer::onlyTrashed()->latest()->get();
        return view('customers.trash', compact('customers'));
    }

    public function restore($id)
    {
        // Restore a deleted customer
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('customers.index')
            ->with('success', 'Customer restored successfully!');
    }

    public function forceDelete($id)
    {
        // Permanently delete a customer
        $customer = Customer::onlyTrashed()->findOrFail($id);

        // Delete related quotations (if requested / required)
        $customer->forceDelete();

        return redirect()->route('customers.trash')
            ->with('success', 'Customer permanently deleted!');
    }

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:customers,id'
        ]);

        Customer::onlyTrashed()->whereIn('id', $request->ids)->restore();

        return redirect()->route('customers.trash')
            ->with('success', count($request->ids) . ' customer(s) restored successfully!');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:customers,id'
        ]);

        $customers = Customer::onlyTrashed()->whereIn('id', $request->ids)->get();

        /** @var \App\Models\Customer $customer */
        foreach ($customers as $customer) {
            $customer->forceDelete();
        }

        return redirect()->route('customers.trash')
            ->with('success', count($request->ids) . ' customer(s) permanently deleted!');
    }
}
