<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$count = DB::table('products')->count();
$masters = DB::table('product_masters')->pluck('product_name');

echo "=== DATABASE STATUS ===\n";
echo "Total products: $count\n\n";
echo "Product Masters:\n";
foreach($masters as $m) {
    $specs = DB::table('products')->where('product_name', $m)->count();
    echo "  - $m: $specs specs\n";
}

echo "\n=== UNIQUE MODELS ===\n";
$models = DB::table('products')->distinct()->pluck('product_model')->sort();
echo "Total unique models: " . count($models) . "\n";
echo "First 10 models:\n";
foreach($models->take(10) as $m) {
    $count = DB::table('products')->where('product_model', $m)->count();
    echo "  - $m: $count specs\n";
}
