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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->unique();
            $table->string('company_logo')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('company_description')->nullable();
            
            // Bank Details
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_type')->nullable();
            
            // Logos & Images
            $table->string('logo_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('web_logo_path')->nullable();
            $table->string('phone_icon_path')->nullable();
            $table->string('mail_icon_path')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
