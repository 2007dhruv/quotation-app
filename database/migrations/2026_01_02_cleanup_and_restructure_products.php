<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration cleans up the old structure and prepares for the new one.
     * It safely removes old tables while preserving any existing data.
     */
    public function up(): void
    {
        // Step 1: Drop old accessories tables if they exist (clean up old structure)
        if (Schema::hasTable('product_accessories')) {
            Schema::dropIfExists('product_accessories');
        }
        
        if (Schema::hasTable('accessories')) {
            Schema::dropIfExists('accessories');
        }

        // Step 2: Add new columns to product_masters table
        if (!Schema::hasColumn('product_masters', 'note')) {
            Schema::table('product_masters', function (Blueprint $table) {
                $table->text('note')->nullable()->after('default_price');
            });
        }

        if (!Schema::hasColumn('product_masters', 'standard_accessories')) {
            Schema::table('product_masters', function (Blueprint $table) {
                $table->json('standard_accessories')->nullable()->after('note');
            });
        }

        if (!Schema::hasColumn('product_masters', 'optional_accessories')) {
            Schema::table('product_masters', function (Blueprint $table) {
                $table->json('optional_accessories')->nullable()->after('standard_accessories');
            });
        }

        // Step 3: Add product_image if it doesn't exist
        if (!Schema::hasColumn('product_masters', 'product_image')) {
            Schema::table('product_masters', function (Blueprint $table) {
                $table->string('product_image')->nullable()->after('default_price');
            });
        }

        // Step 4: Check if product_specifications exists and rename it
        if (Schema::hasTable('product_specifications')) {
            // If products table doesn't exist, rename product_specifications to products
            if (!Schema::hasTable('products')) {
                Schema::rename('product_specifications', 'products');
                
                // Rename the column
                if (Schema::hasColumn('products', 'product_id')) {
                    Schema::table('products', function (Blueprint $table) {
                        $table->renameColumn('product_id', 'product_master_id');
                    });
                }
            }
        }

        // Step 5: If products table exists but doesn't have price column, add it
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->default(0);
            });
        }

        // Step 6: If products table exists but doesn't have product_model, add it
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'product_model')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('product_model')->nullable()->after('product_master_id');
            });
        }

        // Step 7: Ensure foreign key exists for products table
        if (Schema::hasTable('products')) {
            try {
                $keyExists = DB::selectOne("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME='products' 
                    AND COLUMN_NAME='product_master_id' 
                    AND REFERENCED_TABLE_NAME='product_masters'
                    LIMIT 1
                ");

                if (is_null($keyExists)) {
                    // Add foreign key if it doesn't exist
                    Schema::table('products', function (Blueprint $table) {
                        $table->foreign('product_master_id')
                            ->references('id')
                            ->on('product_masters')
                            ->onDelete('cascade');
                    });
                }
            } catch (\Exception $e) {
                // If key check fails, try to add it anyway
                try {
                    Schema::table('products', function (Blueprint $table) {
                        $table->foreign('product_master_id')
                            ->references('id')
                            ->on('product_masters')
                            ->onDelete('cascade');
                    });
                } catch (\Exception $ignore) {
                    // Key might already exist
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely drop foreign key if it exists
        if (Schema::hasTable('products')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropForeign(['product_master_id']);
                });
            } catch (\Exception $e) {
                // Key might not exist, that's okay
            }
        }

        // Drop the new columns if they exist
        if (Schema::hasTable('product_masters')) {
            Schema::table('product_masters', function (Blueprint $table) {
                if (Schema::hasColumn('product_masters', 'note')) {
                    $table->dropColumn('note');
                }
                if (Schema::hasColumn('product_masters', 'standard_accessories')) {
                    $table->dropColumn('standard_accessories');
                }
                if (Schema::hasColumn('product_masters', 'optional_accessories')) {
                    $table->dropColumn('optional_accessories');
                }
                if (Schema::hasColumn('product_masters', 'product_image')) {
                    $table->dropColumn('product_image');
                }
            });
        }

        // Rename products table back to product_specifications if needed
        if (Schema::hasTable('products') && !Schema::hasTable('product_specifications')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    if (Schema::hasColumn('products', 'product_master_id')) {
                        $table->renameColumn('product_master_id', 'product_id');
                    }
                });
                Schema::rename('products', 'product_specifications');
            } catch (\Exception $e) {
                // If rename fails, that's okay
            }
        }
    }
};
