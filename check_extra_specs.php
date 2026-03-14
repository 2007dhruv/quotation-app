<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== APBR-325 SPECS ===\n";
$specs = DB::table('products')->where('product_model', 'APBR-325')->get(['spec_name', 'spec_value']);
foreach($specs as $s) {
    echo "  - {$s->spec_name}: {$s->spec_value}\n";
}

echo "\n=== APBR-412 SPECS ===\n";
$specs = DB::table('products')->where('product_model', 'APBR-412')->get(['spec_name', 'spec_value']);
foreach($specs as $s) {
    echo "  - {$s->spec_name}: {$s->spec_value}\n";
}
