<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CleanDuplicateSpecs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:duplicate-specs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate product specifications from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to clean duplicate specifications...');

        // Get all unique product master IDs
        $productMasterIds = Product::distinct('product_master_id')->pluck('product_master_id');

        $totalDeleted = 0;

        foreach ($productMasterIds as $masterid) {
            // Get all specs for this product master
            $products = Product::where('product_master_id', $masterid)
                ->orderBy('id')
                ->get();

            $seen = [];
            $toDelete = [];

            foreach ($products as $product) {
                // Create a unique key based on spec_name and spec_value
                $key = $product->spec_name . '|' . $product->spec_value;

                if (isset($seen[$key])) {
                    // This is a duplicate, mark for deletion
                    $toDelete[] = $product->id;
                } else {
                    // First occurrence, keep it
                    $seen[$key] = $product->id;
                }
            }

            // Delete duplicates
            if (!empty($toDelete)) {
                $deleted = Product::whereIn('id', $toDelete)->delete();
                $totalDeleted += $deleted;
                $this->line("Product Master ID {$masterid}: Deleted {$deleted} duplicates");
            }
        }

        $this->info("✅ Cleanup completed! Total duplicates removed: {$totalDeleted}");
    }
}
