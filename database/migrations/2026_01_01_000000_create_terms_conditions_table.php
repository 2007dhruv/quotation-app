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
        Schema::create('terms_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // PRICE, WARRANTY, etc.
            $table->longText('description'); // Full T&C text
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['is_active', 'display_order']);
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_conditions');
    }
};
