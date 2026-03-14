<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductMaster;

class CleanAllProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:all-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all products and product masters from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('⚠️  Deleting ALL PRODUCTS AND PRODUCT MASTERS!');

        try {
            // Disable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            $this->info('Deleting all products...');
            Product::truncate();
            $this->line('✅ All products deleted!');

            $this->info('Deleting all product masters...');
            ProductMaster::truncate();
            $this->line('✅ All product masters deleted!');

            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('🎉 Cleanup completed! Database is now clean.');
            $this->line('You can now import your CSV file fresh.');

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
