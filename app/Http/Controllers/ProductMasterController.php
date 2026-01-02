<?php

namespace App\Http\Controllers;

use App\Models\ProductMaster;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;

class ProductMasterController extends Controller
{
    public function index()
    {
        $products = ProductMaster::with('specifications')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_type' => 'nullable|string|max:100',
            'default_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'spec_name' => 'nullable|array',
            'spec_value' => 'nullable|array',
            'spec_unit' => 'nullable|array',
            'standard_accessories' => 'nullable|array',
            'optional_accessories' => 'nullable|array',
        ]);

        // Clean accessory arrays - remove empty/zero values
        $validated['standard_accessories'] = array_filter($validated['standard_accessories'] ?? [], function($id) {
            return !empty($id) && $id != '0' && $id != 0;
        });
        $validated['optional_accessories'] = array_filter($validated['optional_accessories'] ?? [], function($id) {
            return !empty($id) && $id != '0' && $id != 0;
        });

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imagePath = $image->store('products', 'public');
        }

        $product = ProductMaster::create([
            'product_name' => $validated['product_name'],
            'product_type' => $validated['product_type'],
            'default_price' => $validated['default_price'],
            'product_image' => $imagePath ? 'storage/' . $imagePath : null,
        ]);

        // Store specifications
        if (!empty($request->spec_name)) {
            foreach ($request->spec_name as $index => $specName) {
                if (!empty($specName)) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'spec_name' => $specName,
                        'spec_value' => $request->spec_value[$index] ?? null,
                        'spec_unit' => $request->spec_unit[$index] ?? null,
                    ]);
                }
            }
        }

        // Store standard accessories
        if (!empty($validated['standard_accessories'])) {
            foreach ($validated['standard_accessories'] as $accessoryId) {
                $product->accessories()->attach($accessoryId, ['accessory_type' => 'standard']);
            }
        }

        // Store optional accessories
        if (!empty($validated['optional_accessories'])) {
            foreach ($validated['optional_accessories'] as $accessoryId) {
                $product->accessories()->attach($accessoryId, ['accessory_type' => 'optional']);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function edit(ProductMaster $product)
    {
        $product->load('specifications');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, ProductMaster $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_type' => 'nullable|string|max:100',
            'default_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'spec_name' => 'nullable|array',
            'spec_value' => 'nullable|array',
            'spec_unit' => 'nullable|array',
            'standard_accessories' => 'nullable|array',
            'optional_accessories' => 'nullable|array',
        ]);

        // Clean accessory arrays - remove empty/zero values
        $validated['standard_accessories'] = array_filter($validated['standard_accessories'] ?? [], function($id) {
            return !empty($id) && $id != '0' && $id != 0;
        });
        $validated['optional_accessories'] = array_filter($validated['optional_accessories'] ?? [], function($id) {
            return !empty($id) && $id != '0' && $id != 0;
        });

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imagePath = $image->store('products', 'public');
            $validated['product_image'] = 'storage/' . $imagePath;
        }

        $product->update([
            'product_name' => $validated['product_name'],
            'product_type' => $validated['product_type'],
            'default_price' => $validated['default_price'],
            'product_image' => $validated['product_image'] ?? $product->product_image,
        ]);

        // Update specifications
        $product->specifications()->delete();
        if (!empty($request->spec_name)) {
            foreach ($request->spec_name as $index => $specName) {
                if (!empty($specName)) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'spec_name' => $specName,
                        'spec_value' => $request->spec_value[$index] ?? null,
                        'spec_unit' => $request->spec_unit[$index] ?? null,
                    ]);
                }
            }
        }

        // Update accessories - sync with accessory_type
        $standardAccessories = [];
        foreach ($validated['standard_accessories'] as $accessoryId) {
            $standardAccessories[$accessoryId] = ['accessory_type' => 'standard'];
        }

        $optionalAccessories = [];
        foreach ($validated['optional_accessories'] as $accessoryId) {
            $optionalAccessories[$accessoryId] = ['accessory_type' => 'optional'];
        }

        // Merge and sync all accessories
        $allAccessories = array_merge($standardAccessories, $optionalAccessories);
        $product->accessories()->sync($allAccessories);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(ProductMaster $product)
    {
        $product->specifications()->delete();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
