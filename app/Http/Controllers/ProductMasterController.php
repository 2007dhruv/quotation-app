<?php

namespace App\Http\Controllers;

use App\Models\ProductMaster;
use App\Models\Product;
use Illuminate\Http\Request;
use League\Csv\Reader;

class ProductMasterController extends Controller
{
    /**
     * Display a listing of product masters
     */
    public function index()
    {
        $productMasters = ProductMaster::with('products')->orderBy('product_name')->get();
        return view('products.index', compact('productMasters'));
    }

    /**
     * Show the form for creating a new product master
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product master in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255|unique:product_masters',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'note' => 'nullable|string',
            'standard_accessories' => 'nullable|array',
            'standard_accessories.*' => 'nullable|string|max:255',
            'optional_accessories' => 'nullable|array',
            'optional_accessories.*' => 'nullable|string|max:255',
            'specifications_template' => 'nullable|array',
            'specifications_template.*.name' => 'nullable|string|max:255',
            'specifications_template.*.unit' => 'nullable|string|max:100',
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
            $validated['product_image'] = $imagePath;
        }

        // Filter empty accessories and save as JSON
        $validated['standard_accessories'] = $this->filterAndEncodeAccessories(
            $request->input('standard_accessories')
        );
        $validated['optional_accessories'] = $this->filterAndEncodeAccessories(
            $request->input('optional_accessories')
        );

        // Filter empty specifications template and save as JSON
        $validated['specifications_template'] = $this->filterAndEncodeTemplate(
            $request->input('specifications_template')
        );

        ProductMaster::create($validated);

        return redirect()->route('master.index')->with('success', 'Product Master created successfully!');
    }

    /**
     * Show the form for editing the specified product master
     */
    public function edit(ProductMaster $productMaster)
    {
        return view('products.edit', compact('productMaster'));
    }

    /**
     * Display the specified product master with all its models
     */
    public function show(ProductMaster $productMaster)
    {
        $productMaster->load('products');
        return view('products.show', compact('productMaster'));
    }

    /**
     * Update the specified product master in database
     */
    public function update(Request $request, ProductMaster $productMaster)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255|unique:product_masters,product_name,' . $productMaster->id,
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'note' => 'nullable|string',
            'standard_accessories' => 'nullable|array',
            'standard_accessories.*' => 'nullable|string|max:255',
            'optional_accessories' => 'nullable|array',
            'optional_accessories.*' => 'nullable|string|max:255',
            'specifications_template' => 'nullable|array',
            'specifications_template.*.name' => 'nullable|string|max:255',
            'specifications_template.*.unit' => 'nullable|string|max:100',
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
            $validated['product_image'] = $imagePath;
        } else {
            $validated['product_image'] = $productMaster->product_image;
        }

        // Filter empty accessories and save as JSON
        $validated['standard_accessories'] = $this->filterAndEncodeAccessories(
            $request->input('standard_accessories')
        );
        $validated['optional_accessories'] = $this->filterAndEncodeAccessories(
            $request->input('optional_accessories')
        );

        // Filter empty specifications template and save as JSON
        $validated['specifications_template'] = $this->filterAndEncodeTemplate(
            $request->input('specifications_template')
        );

        $productMaster->update($validated);

        return redirect()->route('master.index')->with('success', 'Product Master updated successfully!');
    }



    /**
     * Remove the specified product master from database
     */
    public function destroy(ProductMaster $productMaster)
    {
        $productMaster->products()->delete();
        $productMaster->delete();
        return redirect()->route('master.index')->with('success', 'Product Master deleted successfully!');
    }

    /**
     * Filter empty accessories and return as array
     * @param array|null $accessories
     * @return array|null Array of accessories or null
     */
    private function filterAndEncodeAccessories(?array $accessories): ?array
    {
        if (empty($accessories)) {
            return null;
        }

        // Filter out empty and whitespace-only values, then trim
        $filtered = array_filter(
            array_map('trim', $accessories),
            fn($value) => !empty($value)
        );

        // Return array or null (Eloquent will handle JSON encoding via cast)
        return !empty($filtered) ? array_values($filtered) : null;
    }

    /**
     * Filter empty specification template and return as array of objects
     * @param array|null $template
     * @return array|null Array of {name, unit} objects or null
     */
    private function filterAndEncodeTemplate(?array $template): ?array
    {
        if (empty($template)) {
            return null;
        }

        // Filter out empty entries and format as objects
        $filtered = [];
        foreach ($template as $spec) {
            if (is_array($spec)) {
                $name = trim($spec['name'] ?? '');
                $unit = trim($spec['unit'] ?? '');

                // Only include if name is not empty
                if (!empty($name)) {
                    $filtered[] = [
                        'name' => $name,
                        'unit' => $unit
                    ];
                }
            }
        }

        // Return array or null (Eloquent will handle JSON encoding via cast)
        return !empty($filtered) ? $filtered : null;
    }

    /**
     * Show the CSV import form
     */
    public function importForm()
    {
        return view('products.import');
    }

    /**
     * Handle CSV file import
     */
    public function import(Request $request)
    {
        // Validate file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120'
        ], [
            'csv_file.required' => 'Please select a CSV file to import.',
            'csv_file.mimes' => 'File must be a CSV file.',
            'csv_file.max' => 'File size cannot exceed 5MB.'
        ]);

        try {
            $filePath = $request->file('csv_file')->getRealPath();
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');

            $imported = 0;
            $errors = [];
            $row_number = 1;

            foreach ($csv->getRecords() as $record) {
                $row_number++;

                try {
                    // Handle different column name variations
                    $productName = $record['product_name'] ?? $record['Product Name'] ?? null;
                    $productModel = $record['product_model'] ?? $record['Model'] ?? null;
                    $specName = $record['spec_name'] ?? $record['Specification Name'] ?? null;
                    $specValue = $record['spec_value'] ?? $record['Specification Value'] ?? null;
                    $specUnit = $record['spec_unit'] ?? $record['Unit'] ?? null;
                    $price = $record['price'] ?? $record['Price (INR)'] ?? $record['Price'] ?? null;
                    $stdAccessories = $record['standard_accessories'] ?? $record['Standard Accessories'] ?? null;
                    $optAccessories = $record['optional_accessories'] ?? $record['Optional Accessories'] ?? null;
                    $note = $record['note'] ?? $record['Note'] ?? null;

                    // Validate required fields
                    if (empty($productName) || empty($productModel)) {
                        $errors[] = "Row $row_number: product_name and product_model are required.";
                        continue;
                    }

                    // Parse accessories (separated by semicolon)
                    $stdAccessories = !empty($stdAccessories)
                        ? array_filter(array_map('trim', explode(';', $stdAccessories)))
                        : [];

                    $optAccessories = !empty($optAccessories)
                        ? array_filter(array_map('trim', explode(';', $optAccessories)))
                        : [];

                    // Get or create ProductMaster
                    $productMaster = ProductMaster::firstOrCreate(
                        ['product_name' => trim($productName)],
                        [
                            'standard_accessories' => !empty($stdAccessories) ? $stdAccessories : null,
                            'optional_accessories' => !empty($optAccessories) ? $optAccessories : null,
                            'note' => !empty($note) ? trim($note) : null
                        ]
                    );

                    // Prepare product data
                    $productData = [
                        'product_master_id' => $productMaster->id,
                        'product_model' => trim($productModel),
                        'spec_name' => !empty($specName) ? trim($specName) : trim($productModel),
                        'spec_value' => !empty($specValue) ? trim($specValue) : null,
                        'spec_unit' => !empty($specUnit) ? trim($specUnit) : null,
                        'price' => !empty($price) ? (float) $price : 0
                    ];

                    // Create or update product
                    Product::updateOrCreate(
                        [
                            'product_master_id' => $productMaster->id,
                            'product_model' => $productData['product_model'],
                            'spec_name' => $productData['spec_name']
                        ],
                        $productData
                    );

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row $row_number: " . $e->getMessage();
                    continue;
                }
            }

            // Prepare response
            $message = "✅ Successfully imported $imported product(s)!";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " error(s) found)";
            }

            return back()
                ->with('success', $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            return back()
                ->with('error', '❌ CSV Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}
