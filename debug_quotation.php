<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get latest quotation
$quotation = DB::table('quotations')->latest()->first();
echo "=== LATEST QUOTATION ===\n";
echo "ID: {$quotation->id}\n";

// Get items in this quotation
$items = DB::table('quotation_items')->where('quotation_id', $quotation->id)->get();
echo "\nQuotation Items:\n";
foreach($items as $item) {
    echo "  Item ID: {$item->id}\n";
    echo "    product_id: {$item->product_id}\n";
    echo "    product_type: {$item->product_type}\n";
    echo "    product_name: {$item->product_name}\n";
    
    // Check what's in products table for this product_id
    $product = DB::table('products')->find($item->product_id);
    if($product) {
        echo "    In DB -> product_model: {$product->product_model}\n";
        $specs = DB::table('products')->where('product_model', $product->product_model)->count();
        echo "    Specs for this model: $specs\n";
    }
}
