<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRODUCTS TABLE STRUCTURE ===\n";
$columns = DB::getSchemaBuilder()->getColumnListing('products');
foreach($columns as $col) {
    echo "  - $col\n";
}

echo "\n=== DATABASE STATUS ===\n";
$count = DB::table('products')->count();
echo "Total products: $count\n";

$masters = DB::table('product_masters')->get(['id', 'product_name']);
echo "\nProduct Masters:\n";
foreach($masters as $m) {
    $specs = DB::table('products')->where('product_master_id', $m->id)->count();
    echo "  - $m->product_name (ID: $m->id): $specs specs\n";
}

echo "\n=== UNIQUE MODELS (Sample) ===\n";
$models = DB::table('products')->distinct()->pluck('product_model')->sort()->take(15);
echo "Sample models:\n";
foreach($models as $m) {
    $count = DB::table('products')->where('product_model', $m)->count();
    echo "  - $m: $count specs\n";
}
