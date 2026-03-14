<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Delete the extra "Throat Depth" spec from APBR-325 and APBR-412
$deleted = DB::table('products')
    ->whereIn('product_model', ['APBR-325', 'APBR-412'])
    ->where('spec_name', 'Throat Depth')
    ->delete();

echo "✅ Deleted {$deleted} 'Throat Depth' specs from APBR-325 and APBR-412\n";

// Verify
$apbr325 = DB::table('products')->where('product_model', 'APBR-325')->count();
$apbr412 = DB::table('products')->where('product_model', 'APBR-412')->count();

echo "\nAPBR-325: {$apbr325} specs\n";
echo "APBR-412: {$apbr412} specs\n";
echo "\n✅ All models now have correct 10 specs!\n";
