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
        if (!Schema::hasTable('accessories')) {
            Schema::create('accessories', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g., "Hydraulic Pump", "Digital Display"
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('is_active');
                $table->index('name');
            });
        }

        if (!Schema::hasTable('product_accessories')) {
            Schema::create('product_accessories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('product_masters')->onDelete('cascade');
                $table->foreignId('accessory_id')->constrained('accessories')->onDelete('cascade');
                $table->enum('accessory_type', ['standard', 'optional'])->default('optional');
                $table->timestamps();
                
                // Use explicit shorter names to avoid MySQL identifier length limit (64 chars)
                $table->unique(['product_id', 'accessory_id', 'accessory_type'], 'prod_acc_unique');
                $table->index(['product_id', 'accessory_type'], 'prod_acc_type_idx');
            });
        }
    }

    /**
     * Reverse the migrations.  
     */
    public function down(): void
    {
        Schema::dropIfExists('product_accessories');
        Schema::dropIfExists('accessories');
    }
};
