<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$specs = DB::table('products')
    ->groupBy('product_model')
    ->selectRaw('product_model, COUNT(*) as spec_count')
    ->orderBy('spec_count', 'desc')
    ->get();

echo "=== SPECIFICATION COUNT PER MODEL ===\n";
foreach($specs as $s) {
    $color = $s->spec_count > 10 ? "❌ TOO MANY" : "✅ CORRECT";
    echo "{$s->product_model}: {$s->spec_count} specs {$color}\n";
}
