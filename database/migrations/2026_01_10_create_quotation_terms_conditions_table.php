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
        Schema::create('quotation_terms_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('terms_condition_id')->constrained('terms_conditions')->onDelete('cascade');
            $table->timestamps();

            // Ensure no duplicate entries with shorter constraint name
            $table->unique(['quotation_id', 'terms_condition_id'], 'quot_terms_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_terms_conditions');
    }
};
