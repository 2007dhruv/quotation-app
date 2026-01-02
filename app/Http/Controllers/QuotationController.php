<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProductMaster;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->paginate(10);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = ProductMaster::with('specifications')->get();
        return view('quotations.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:product_masters,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Get customer and determine tax percent based on GST type
        $customer = Customer::findOrFail($request->customer_id);
        $taxPercent = ($customer->gst_type == 'instate') ? 18 : 18;

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

        // Create quotation
        $quotation = Quotation::create([
            'quotation_number' => Quotation::generateQuotationNumber(),
            'customer_id' => $request->customer_id,
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        // Create quotation items
        foreach ($request->items as $item) {
            $product = ProductMaster::find($item['product_id']);
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $item['product_id'],
                'product_name' => $product->product_name,
                'product_type' => $product->product_type,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'description' => $item['description'] ?? null,
            ]);
        }

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product']);
        return view('quotations.show', compact('quotation'));
    }

    public function generatePdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product.specifications']);
        
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('quotation-' . $quotation->quotation_number . '.pdf');
    }

    public function streamPdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product.specifications']);
        
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('quotation-' . $quotation->quotation_number . '.pdf');
    }

    public function generatePdf2(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product.specifications']);
        
        $pdf = Pdf::loadView('quotations.pdf2', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('quotation-' . $quotation->quotation_number . '.pdf');
    }

    public function streamPdf2(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.product.specifications']);
        
        $pdf = Pdf::loadView('quotations.pdf2', compact('quotation'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('quotation-' . $quotation->quotation_number . '.pdf');
    }

    // API endpoints for AJAX
    public function searchCustomers(Request $request)
    {
        $search = $request->get('search', '');
        $customers = Customer::where('customer_name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'customer_name', 'address', 'city', 'mobile', 'gst_no']);
        
        return response()->json($customers);
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        $type = $request->get('type', '');
        
        $query = ProductMaster::with('specifications');
        
        if ($search) {
            $query->where('product_name', 'like', "%{$search}%");
        }
        
        if ($type) {
            $query->where('product_type', $type);
        }
        
        $products = $query->limit(10)->get();
        
        return response()->json($products);
    }

    public function getProductTypes()
    {
        $types = ProductMaster::distinct()->pluck('product_type')->filter();
        return response()->json($types);
    }
}
