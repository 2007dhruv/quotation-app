<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_masters', function (Blueprint $table) {
            // Drop unnecessary columns, keep only: id, product_name, note, standard_accessories, optional_accessories, product_image
            if (Schema::hasColumn('product_masters', 'product_type')) {
                $table->dropColumn('product_type');
            }
            if (Schema::hasColumn('product_masters', 'default_price')) {
                $table->dropColumn('default_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_masters', function (Blueprint $table) {
            // Re-add the columns in case of rollback
            if (!Schema::hasColumn('product_masters', 'product_type')) {
                $table->string('product_type')->nullable();
            }
            if (!Schema::hasColumn('product_masters', 'default_price')) {
                $table->decimal('default_price', 10, 2)->default(0);
            }
        });
    }
};
