<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\ProductMaster;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\TermsCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->whereNull('deleted_at')->latest()->get();
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $companies = Company::all();
        $customers = Customer::all();
        $termsConditions = TermsCondition::where('is_active', true)->orderBy('display_order')->get();
        // Get grouped products (models with their specs)
        $allProducts = \App\Models\Product::with('master')->get();
        $products = $allProducts->groupBy(function ($product) {
            return $product->product_master_id . '-' . $product->product_model . '-' . $product->price;
        })->map(function ($group) {
            $first = $group->first();
            $specs = $group->map(function ($product) {
                return [
                    'spec_name' => $product->spec_name,
                    'spec_value' => $product->spec_value,
                    'spec_unit' => $product->spec_unit,
                ];
            })->toArray();

            return [
                'id' => $first->id,
                'product_master_id' => $first->product_master_id,
                'product_model' => $first->product_model,
                'price' => $first->price,
                'master' => $first->master,
                'specs' => $specs,
                'spec_count' => count($specs),
                'product_name' => $first->master->product_name . ' (' . $first->product_model . ')',
            ];
        })->values();

        return view('quotations.create', compact('companies', 'customers', 'products', 'termsConditions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'quotation_letter_body' => 'nullable|string|max:10000',
            'subject' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:product_masters,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Get customer and determine tax percent based on GST type
        $customer = Customer::findOrFail($request->customer_id);
        $taxPercent = ($customer->gst_type == 'instate') ? 18 : 18;

        // Generate quotation number with retry (max 10 retries)
        $quotationNumber = null;
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $tempNumber = Quotation::generateQuotationNumber($request->company_id);

            // Check if this number already exists as active (not soft-deleted)
            $exists = Quotation::where('quotation_number', $tempNumber)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                $quotationNumber = $tempNumber;
                break;
            }
        }

        if (!$quotationNumber) {
            return back()->withErrors(['quotation_number' => 'Could not generate unique quotation number. Please try again.']);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxAmount = ($subtotal * $taxPercent) / 100;

        // Calculate discount from discount_percent
        $discountPercent = $request->discount_percent ?? 0;
        $discountAmount = ($subtotal * $discountPercent) / 100;

        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        // Create quotation with company_id
        $quotation = Quotation::create([
            'quotation_number' => $quotationNumber,
            'company_id' => $request->company_id,
            'customer_id' => $request->customer_id,
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'quotation_letter_body' => $request->quotation_letter_body,
            'subject' => $request->subject,
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        // Create quotation items - LOOP through each product and insert one row per product
        foreach ($request->items as $item) {
            // item['product_id'] is actually product_master_id from the search results
            // Get the ProductMaster and create a QuotationItem
            $productMaster = \App\Models\ProductMaster::findOrFail($item['product_id']);

            // Get the first Product record for this master (to get the model number)
            $product = \App\Models\Product::where('product_master_id', $item['product_id'])
                ->first();

            // Get the product_type/model from the product record
            $productModel = $product ? $product->product_model : ($item['product_type'] ?? 'N/A');

            // Create quotation item with product_master_id as product_id
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $productMaster->id,  // Store product_master_id
                'product_name' => $productMaster->product_name . ' (' . $productModel . ')',  // Full name with model
                'product_type' => $productModel,  // Just the model number
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'description' => $item['description'] ?? null,
            ]);
        }

        // Attach terms & conditions if selected
        if ($request->has('terms_conditions') && !empty($request->terms_conditions)) {
            $termsConditionIds = array_filter($request->terms_conditions);
            if (!empty($termsConditionIds)) {
                $quotation->termsConditions()->attach($termsConditionIds);
            }
        }

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product', 'termsConditions']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load(['customer', 'items', 'termsConditions']);
        $companies = Company::all();
        $termsConditions = TermsCondition::where('is_active', true)->orderBy('display_order')->get();
        $customers = Customer::orderBy('customer_name')->get();

        return view('quotations.edit', compact('quotation', 'companies', 'termsConditions', 'customers'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        // Validate input
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'quotation_letter_body' => 'nullable|string|max:10000',
            'subject' => 'nullable|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:quotation_items,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // DEBUG: Log what was received
        \Log::info('UPDATE QUOTATION - Received Data', [
            'quotation_id' => $quotation->id,
            'quotation_number' => $quotation->quotation_number,
            'letter_body_received' => !empty($validated['quotation_letter_body']),
            'letter_body_length' => strlen($validated['quotation_letter_body'] ?? ''),
            'letter_body_preview' => substr($validated['quotation_letter_body'] ?? '', 0, 100),
        ]);

        // Check if company changed
        $companyChanged = $validated['company_id'] != $quotation->company_id;


        // Update quotation basic info
        $updateData = [
            'company_id' => $validated['company_id'],
            'customer_id' => $validated['customer_id'],
            'quotation_date' => $validated['quotation_date'],
            'valid_until' => $validated['valid_until'],
            'quotation_letter_body' => $validated['quotation_letter_body'],
            'subject' => $validated['subject'] ?? null,
            'discount_percent' => $validated['discount_percent'],
            'tax_percent' => $validated['tax_percent'],
            'subtotal' => $request->input('subtotal'),
            'discount_amount' => $request->input('discount_amount'),
            'tax_amount' => $request->input('tax_amount'),
            'total_amount' => $request->input('total_amount'),
        ];

        // If company changed, regenerate quotation number
        if ($companyChanged) {
            $updateData['quotation_number'] = Quotation::generateQuotationNumber($validated['company_id']);
        }

        $quotation->update($updateData);

        // Update quotation items
        foreach ($validated['items'] as $itemData) {
            $item = QuotationItem::findOrFail($itemData['id']);
            $totalPrice = $itemData['quantity'] * $itemData['unit_price'];

            $item->update([
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        // Update terms & conditions
        if ($request->has('terms_conditions')) {
            $termsConditionIds = array_filter($request->terms_conditions ?? []);
            $quotation->termsConditions()->sync($termsConditionIds);
        } else {
            $quotation->termsConditions()->detach();
        }

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation updated successfully!');
    }

    public function generatePdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product']);

        // Convert image paths to absolute file paths for DOMPDF
        $company = \App\Models\Company::getPrimary();
        if ($company) {
            if ($company->web_logo_path) {
                $company->web_logo_file_path = storage_path('app/public/' . $company->web_logo_path);
            }
            if ($company->phone_icon_path) {
                $company->phone_icon_file_path = storage_path('app/public/' . $company->phone_icon_path);
            }
            if ($company->mail_icon_path) {
                $company->mail_icon_file_path = storage_path('app/public/' . $company->mail_icon_path);
            }
            if ($company->qr_code_path) {
                $company->qr_code_file_path = storage_path('app/public/' . $company->qr_code_path);
            }
        }

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('quotation-' . str_replace('/', '-', $quotation->quotation_number) . '.pdf');
    }

    public function streamPdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product']);

        // Convert image paths to absolute file paths for DOMPDF
        $company = \App\Models\Company::getPrimary();
        if ($company) {
            if ($company->web_logo_path) {
                $company->web_logo_file_path = storage_path('app/public/' . $company->web_logo_path);
            }
            if ($company->phone_icon_path) {
                $company->phone_icon_file_path = storage_path('app/public/' . $company->phone_icon_path);
            }
            if ($company->mail_icon_path) {
                $company->mail_icon_file_path = storage_path('app/public/' . $company->mail_icon_path);
            }
            if ($company->qr_code_path) {
                $company->qr_code_file_path = storage_path('app/public/' . $company->qr_code_path);
            }
        }

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('quotation-' . str_replace('/', '-', $quotation->quotation_number) . '.pdf');
    }

    public function generatePdf2(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product', 'termsConditions']);
        $termsConditions = $quotation->termsConditions;

        // Load specifications for each item
        $quotation->items->each(function ($item) {
            if ($item->product) {
                $item->specifications = \App\Models\Product::where('product_master_id', $item->product->product_master_id)
                    ->where('product_model', $item->product->product_model)
                    ->where('price', $item->product->price)
                    ->get()
                    ->filter(fn($p) => $p->spec_name)
                    ->unique(fn($p) => $p->spec_name . '-' . $p->spec_value)
                    ->values();
            }
        });

        // Convert image paths to absolute file paths for DOMPDF
        $company = \App\Models\Company::getPrimary();
        if ($company) {
            if ($company->logo_path) {
                $company->logo_file_path = storage_path('app/public/' . $company->logo_path);
            }
            if ($company->web_logo_path) {
                $company->web_logo_file_path = storage_path('app/public/' . $company->web_logo_path);
            }
            if ($company->phone_icon_path) {
                $company->phone_icon_file_path = storage_path('app/public/' . $company->phone_icon_path);
            }
            if ($company->mail_icon_path) {
                $company->mail_icon_file_path = storage_path('app/public/' . $company->mail_icon_path);
            }
            if ($company->qr_code_path) {
                $company->qr_code_file_path = storage_path('app/public/' . $company->qr_code_path);
            }
        }

        $pdf = Pdf::loadView('quotations.pdf2', compact('quotation', 'termsConditions'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('quotation-' . str_replace('/', '-', $quotation->quotation_number) . '.pdf');
    }

    public function streamPdf2(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product', 'termsConditions']);
        $termsConditions = $quotation->termsConditions;

        // Load specifications for each item
        $quotation->items->each(function ($item) {
            if ($item->product) {
                $item->specifications = \App\Models\Product::where('product_master_id', $item->product->product_master_id)
                    ->where('product_model', $item->product->product_model)
                    ->where('price', $item->product->price)
                    ->get()
                    ->filter(fn($p) => $p->spec_name)
                    ->unique(fn($p) => $p->spec_name . '-' . $p->spec_value)
                    ->values();
            }
        });

        // Convert image paths to absolute file paths for DOMPDF
        $company = \App\Models\Company::getPrimary();
        if ($company) {
            if ($company->logo_path) {
                $company->logo_file_path = storage_path('app/public/' . $company->logo_path);
            }
            if ($company->web_logo_path) {
                $company->web_logo_file_path = storage_path('app/public/' . $company->web_logo_path);
            }
            if ($company->phone_icon_path) {
                $company->phone_icon_file_path = storage_path('app/public/' . $company->phone_icon_path);
            }
            if ($company->mail_icon_path) {
                $company->mail_icon_file_path = storage_path('app/public/' . $company->mail_icon_path);
            }
            if ($company->qr_code_path) {
                $company->qr_code_file_path = storage_path('app/public/' . $company->qr_code_path);
            }
        }

        $pdf = Pdf::loadView('quotations.pdf2', compact('quotation', 'termsConditions'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('quotation-' . str_replace('/', '-', $quotation->quotation_number) . '.pdf');
    }

    // API endpoints for AJAX
    public function searchCustomers(Request $request)
    {
        $search = $request->get('search', '');
        
        if (empty($search)) {
            return response()->json([]);
        }

        // Normalize search string
        $cleanSearch = strtolower(trim($search));
        $terms = array_filter(explode(' ', $cleanSearch), function($term) {
            return strlen($term) > 1;
        });

        $query = Customer::query();

        // Use raw SQL to get relevance score and handle case insensitivity efficiently
        // Scoring: 
        // 10 points for exact name match
        // 5 points if name starts with search
        // 3 points if name contains search
        // 1 point for each additional term match
        
        $query->select('*')
            ->selectRaw("
                (CASE 
                    WHEN LOWER(customer_name) = ? THEN 10
                    WHEN LOWER(customer_name) LIKE ? THEN 5
                    WHEN LOWER(customer_name) LIKE ? THEN 3
                    ELSE 0
                END) AS relevance_score
            ", [$cleanSearch, "{$cleanSearch}%", "%{$cleanSearch}%"]);

        $query->where(function ($q) use ($cleanSearch, $terms) {
            // Main search on name, city, address
            $q->whereRaw("LOWER(customer_name) LIKE ?", ["%{$cleanSearch}%"])
              ->orWhereRaw("LOWER(address) LIKE ?", ["%{$cleanSearch}%"])
              ->orWhereRaw("LOWER(city) LIKE ?", ["%{$cleanSearch}%"])
              ->orWhereRaw("LOWER(mobile) LIKE ?", ["%{$cleanSearch}%"]);

            // OR match individual words if there are multiple
            if (count($terms) > 1) {
                foreach ($terms as $term) {
                    $q->orWhereRaw("LOWER(customer_name) LIKE ?", ["%{$term}%"]);
                }
            }
        });

        $customers = $query->orderBy('relevance_score', 'desc')
            ->orderBy('customer_name', 'asc')
            ->limit(15)
            ->get(['id', 'customer_name', 'address', 'city', 'mobile', 'gst_no', 'gst_type', 'relevance_score']);

        return response()->json($customers);
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        
        if (empty($search)) {
            return response()->json([]);
        }

        // Normalize search: lowercase, remove special chars, trim
        $cleanSearch = strtolower(trim(str_replace("'", "", $search)));
        $terms = array_filter(explode(' ', $cleanSearch), function($term) {
            return strlen($term) > 1;
        });

        // Get all products matching the search criteria with relevance scoring
        $query = \App\Models\Product::with('master');

        // scoring Raw SQL
        // 10: exact match on model or master name
        // 5: starts with search
        // 3: contains search
        // 1: matches specs
        $query->select('*')
            ->selectRaw("
                (CASE 
                    WHEN LOWER(REPLACE(product_model, '''', '')) = ? THEN 10
                    WHEN LOWER(REPLACE(product_model, '''', '')) LIKE ? THEN 5
                    WHEN LOWER(REPLACE(product_model, '''', '')) LIKE ? THEN 3
                    ELSE 0
                END) + 
                (CASE 
                    WHEN EXISTS (SELECT 1 FROM product_masters pm WHERE pm.id = products.product_master_id AND LOWER(REPLACE(pm.product_name, '''', '')) = ?) THEN 10
                    WHEN EXISTS (SELECT 1 FROM product_masters pm WHERE pm.id = products.product_master_id AND LOWER(REPLACE(pm.product_name, '''', '')) LIKE ?) THEN 5
                    WHEN EXISTS (SELECT 1 FROM product_masters pm WHERE pm.id = products.product_master_id AND LOWER(REPLACE(pm.product_name, '''', '')) LIKE ?) THEN 3
                    ELSE 0
                END) AS relevance_score
            ", [
                $cleanSearch, "{$cleanSearch}%", "%{$cleanSearch}%",
                $cleanSearch, "{$cleanSearch}%", "%{$cleanSearch}%"
            ]);

        $query->where(function ($q) use ($terms, $cleanSearch) {
            // Main search on model, master name, specs
            $q->whereRaw("LOWER(REPLACE(product_model, '''', '')) LIKE ?", ["%{$cleanSearch}%"])
              ->orWhereHas('master', function ($masterQ) use ($cleanSearch) {
                  $masterQ->whereRaw("LOWER(REPLACE(product_name, '''', '')) LIKE ?", ["%{$cleanSearch}%"]);
              })
              ->orWhereRaw("LOWER(spec_name) LIKE ?", ["%{$cleanSearch}%"])
              ->orWhereRaw("LOWER(spec_value) LIKE ?", ["%{$cleanSearch}%"]);

            // Match individual terms if multiple
            if (count($terms) > 1) {
                foreach ($terms as $term) {
                    $q->orWhereRaw("LOWER(REPLACE(product_model, '''', '')) LIKE ?", ["%{$term}%"]);
                }
            }
        });

        $allProducts = $query->orderBy('relevance_score', 'desc')
            ->orderBy('product_model', 'asc')
            ->get();

        // Group by product_master_id and product_model to get unique models
        // ... (remaining grouping logic remains the same below)
        $grouped = $allProducts->groupBy(function ($product) {
            return $product->product_master_id . '-' . $product->product_model . '-' . $product->price;
        })->map(function ($group) {
            $first = $group->first();
            $specs = $group->map(function ($product) {
                return [
                    'spec_name' => $product->spec_name,
                    'spec_value' => $product->spec_value,
                    'spec_unit' => $product->spec_unit,
                ];
            })->toArray();

            return [
                'id' => $first->product_master_id,  // Return product_master_id
                'product_master_id' => $first->product_master_id,
                'product_model' => $first->product_model,
                'price' => $first->price,
                'master_name' => $first->master->product_name,
                'product_name' => $first->master->product_name . ' (' . $first->product_model . ')',
                'default_price' => $first->price,
                'specifications' => $specs,
            ];
        })->values();

        return response()->json($grouped);
    }

    public function getProductTypes()
    {
        // Get unique product master names (main products)
        $types = ProductMaster::distinct()->pluck('product_name')->filter();
        return response()->json($types);
    }

    public function destroy(Quotation $quotation)
    {
        // Soft delete the quotation (moves to trash)
        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation moved to trash!');
    }

    public function trash()
    {
        // Show only deleted quotations
        $quotations = Quotation::with('customer')->onlyTrashed()->latest()->get();
        return view('quotations.trash', compact('quotations'));
    }

    public function restore($id)
    {
        // Restore a deleted quotation
        $quotation = Quotation::onlyTrashed()->findOrFail($id);
        $quotation->restore();

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation restored successfully!');
    }

    public function forceDelete($id)
    {
        // Permanently delete a quotation
        $quotation = Quotation::onlyTrashed()->findOrFail($id);
        $quotation->items()->forceDelete();
        $quotation->forceDelete();

        return redirect()->route('quotations.trash')
            ->with('success', 'Quotation permanently deleted!');
    }

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quotations,id'
        ]);

        Quotation::onlyTrashed()->whereIn('id', $request->ids)->restore();

        return redirect()->route('quotations.trash')
            ->with('success', count($request->ids) . ' quotation(s) restored successfully!');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quotations,id'
        ]);

        $quotations = Quotation::onlyTrashed()->whereIn('id', $request->ids)->get();

        /** @var \App\Models\Quotation $quotation */
        foreach ($quotations as $quotation) {
            $quotation->items()->forceDelete();
            $quotation->forceDelete();
        }

        return redirect()->route('quotations.trash')
            ->with('success', count($request->ids) . ' quotation(s) permanently deleted!');
    }
}
