<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMaster;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products (detailed)
     */
    public function index()
    {
        // Get all products with their masters
        $allProducts = Product::with('master')->get();

        // Group products by product_master_id + product_model + default_price
        $products = $allProducts->groupBy(function ($product) {
            return $product->product_master_id . '-' . $product->product_model . '-' . $product->default_price;
        })->map(function ($group) {
            // Get first product as reference (same master, model, price)
            $first = $group->first();

            // Collect all specifications
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
                'price' => $first->default_price,
                'master' => $first->master,
                'specs' => $specs,
                'spec_count' => count($specs),
            ];
        })->values();

        return view('products.products-index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create(Request $request)
    {
        $productMasters = ProductMaster::orderBy('product_name')->get();
        $preselectedMasterId = $request->query('master_id');
        $preselectedMaster = null;

        if ($preselectedMasterId) {
            $preselectedMaster = ProductMaster::find($preselectedMasterId);
        }

        return view('products.product-create', compact('productMasters', 'preselectedMasterId', 'preselectedMaster'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(Request $request)
    {
        // Support both single product and multiple products
        // Check if we have products array (multi-row form) or single product
        $isMultiProduct = !empty($request->input('products')) && is_array($request->input('products'));

        $validated = $request->validate([
            'product_master_id' => 'required|exists:product_masters,id',
            // Single product mode
            'product_model' => $isMultiProduct ? 'nullable' : 'required|string|max:255',
            'spec_name' => 'nullable|array',
            'spec_value' => 'nullable|array',
            'spec_unit' => 'nullable|array',
            'price' => $isMultiProduct ? 'nullable' : 'required|numeric|min:0',
            // Multi-product mode
            'products' => $isMultiProduct ? 'required|array' : 'nullable|array',
            'products.*.product_model' => 'nullable|string|max:255',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.specs' => 'nullable|array',
            'products.*.specs.*.name' => 'nullable|string',
            'products.*.specs.*.value' => 'nullable|string',
            'products.*.specs.*.unit' => 'nullable|string',
        ]);

        $masterProductId = $validated['product_master_id'];
        $allSpecifications = [];

        // MULTI-PRODUCT MODE: products array provided
        if (!empty($request->input('products')) && is_array($request->input('products'))) {
            $products = $request->input('products');

            foreach ($products as $productIndex => $productData) {
                $model = trim($productData['product_model'] ?? '');
                $price = $productData['price'] ?? 0;

                // Skip empty model rows
                if (empty($model) || !is_numeric($price) || $price < 0) {
                    continue;
                }

                // Check if product has specific specs with values (per-product specifications from table)
                $productSpecs = $productData['specs'] ?? [];

                if (!empty($productSpecs) && is_array($productSpecs)) {
                    // PER-PRODUCT SPECIFICATIONS FROM TABLE
                    foreach ($productSpecs as $spec) {
                        $specName = trim($spec['name'] ?? '');
                        $specValue = trim($spec['value'] ?? '');

                        // Only create if spec name exists (skip empty columns)
                        if (!empty($specName)) {
                            $allSpecifications[] = [
                                'product_master_id' => $masterProductId,
                                'product_model' => $model,
                                'spec_name' => $specName,
                                'spec_value' => $specValue,
                                'spec_unit' => $spec['unit'] ?? '',
                                'price' => $price,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                } else {
                    // FALLBACK: Use shared specifications from spec_name[], spec_value[], spec_unit[]
                    $specNames = $request->input('spec_name') ?? [];
                    $specValues = $request->input('spec_value') ?? [];
                    $specUnits = $request->input('spec_unit') ?? [];

                    if (!empty($specNames) && is_array($specNames)) {
                        foreach ($specNames as $index => $specName) {
                            if (!empty(trim($specName))) {
                                $allSpecifications[] = [
                                    'product_master_id' => $masterProductId,
                                    'product_model' => $model,
                                    'spec_name' => trim($specName),
                                    'spec_value' => $specValues[$index] ?? '',
                                    'spec_unit' => $specUnits[$index] ?? '',
                                    'price' => $price,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    } else {
                        // No specs provided, create default
                        $allSpecifications[] = [
                            'product_master_id' => $masterProductId,
                            'product_model' => $model,
                            'spec_name' => 'Default',
                            'spec_value' => '',
                            'spec_unit' => '',
                            'price' => $price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }
        // SINGLE-PRODUCT MODE: backward compatible with existing form
        else {
            $model = trim($validated['product_model'] ?? '');
            $price = $validated['price'] ?? 0;

            if (!empty($model) && is_numeric($price) && $price >= 0) {
                // Collect non-empty specifications
                if (!empty($request->spec_name) && is_array($request->spec_name)) {
                    foreach ($request->spec_name as $index => $specName) {
                        if (!empty(trim($specName))) {
                            $allSpecifications[] = [
                                'product_master_id' => $masterProductId,
                                'product_model' => $model,
                                'spec_name' => trim($specName),
                                'spec_value' => $request->spec_value[$index] ?? '',
                                'spec_unit' => $request->spec_unit[$index] ?? '',
                                'price' => $price,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }

                // If no specifications provided, create one product row with empty specs
                if (empty($allSpecifications)) {
                    $allSpecifications[] = [
                        'product_master_id' => $masterProductId,
                        'product_model' => $model,
                        'spec_name' => 'Default',
                        'spec_value' => '',
                        'spec_unit' => '',
                        'price' => $price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert all specifications at once
        if (!empty($allSpecifications)) {
            Product::insert($allSpecifications);
        }

        $count = count(array_unique(array_column($allSpecifications, 'product_model')));
        return redirect()->route('master.show', $masterProductId)
            ->with('success', "Successfully created $count product model(s)!");
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $productMasters = ProductMaster::orderBy('product_name')->get();
        $product->load('master');

        // Get ALL specifications for this product model
        $allSpecs = Product::where('product_model', $product->product_model)
            ->where('product_master_id', $product->product_master_id)
            ->get();

        return view('products.product-edit', compact('product', 'productMasters', 'allSpecs'));
    }

    /**
     * Update the specified product in database
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_master_id' => 'required|exists:product_masters,id',
            'product_model' => 'required|string|max:255',
            'spec_name' => 'nullable|array',
            'spec_value' => 'nullable|array',
            'spec_unit' => 'nullable|array',
            'price' => 'required|numeric|min:0',
        ]);

        // Update product base info (model, master, price)
        $product->update([
            'product_master_id' => $validated['product_master_id'],
            'product_model' => $validated['product_model'],
            'price' => $validated['price'],
        ]);

        // Delete all old specs for this product model
        Product::where('product_model', $product->product_model)
            ->where('product_master_id', $validated['product_master_id'])
            ->delete();

        // Create new specs from form input
        $allSpecifications = [];
        $specNames = $validated['spec_name'] ?? [];
        $specValues = $validated['spec_value'] ?? [];
        $specUnits = $validated['spec_unit'] ?? [];

        if (!empty($specNames) && is_array($specNames)) {
            foreach ($specNames as $index => $specName) {
                if (!empty(trim($specName))) {
                    $allSpecifications[] = [
                        'product_master_id' => $validated['product_master_id'],
                        'product_model' => $validated['product_model'],
                        'spec_name' => trim($specName),
                        'spec_value' => $specValues[$index] ?? '',
                        'spec_unit' => $specUnits[$index] ?? '',
                        'price' => $validated['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // If no specs, create default one
        if (empty($allSpecifications)) {
            $allSpecifications[] = [
                'product_master_id' => $validated['product_master_id'],
                'product_model' => $validated['product_model'],
                'spec_name' => 'Default',
                'spec_value' => '',
                'spec_unit' => '',
                'price' => $validated['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all specs
        Product::insert($allSpecifications);

        return redirect()->route('master.show', $validated['product_master_id'])
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from database
     */
    public function destroy(Product $product)
    {
        try {
            $productMasterId = $product->product_master_id;
            $productId = $product->id;

            // Log the deletion attempt
            \Log::info('Attempting to delete product: ' . $productId);

            $product->delete();

            // Log successful deletion
            \Log::info('Product deleted successfully: ' . $productId);

            return redirect()->route('master.show', $productMasterId)
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Delete all products with a specific model name (entire model)
     */
    public function destroyModel(ProductMaster $productMaster, $modelName)
    {
        try {
            // Find all products with this model name and master
            $productsToDelete = Product::where('product_master_id', $productMaster->id)
                ->where('product_model', $modelName)
                ->get();

            $deleteCount = $productsToDelete->count();

            \Log::info("Attempting to delete model '{$modelName}' with {$deleteCount} products from master {$productMaster->id}");

            // Delete all products with this model
            Product::where('product_master_id', $productMaster->id)
                ->where('product_model', $modelName)
                ->delete();

            \Log::info("Successfully deleted model '{$modelName}' with {$deleteCount} products");

            return redirect()->route('master.show', $productMaster->id)
                ->with('success', "Model '{$modelName}' and all its {$deleteCount} specifications deleted successfully!");
        } catch (\Exception $e) {
            \Log::error("Error deleting model '{$modelName}': " . $e->getMessage());
            return redirect()->back()
                ->with('error', "Error deleting model: " . $e->getMessage());
        }
    }

    /**
     * Get products for a specific product master (API endpoint)
     */
    public function getByMaster($productMasterId)
    {
        $products = Product::where('product_master_id', $productMasterId)
            ->select('id', 'product_model', 'spec_name', 'spec_value', 'spec_unit', 'default_price')
            ->get();
        return response()->json($products);
    }
    /**
     * Get specifications template for a product master (AJAX)
     */
    public function getTemplateByMaster($productMasterId)
    {
        $productMaster = ProductMaster::findOrFail($productMasterId);
        $template = $productMaster->getSpecificationsTemplateArray();

        return response()->json([
            'template' => $template,
            'count' => count($template)
        ]);
    }
}
