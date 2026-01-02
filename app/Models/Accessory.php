<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    protected $table = 'accessories';

    protected $fillable = [
        'name',
        'description',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get all products that have this accessory
     */
    public function products()
    {
        return $this->belongsToMany(ProductMaster::class, 'product_accessories')
                    ->withPivot('accessory_type')
                    ->withTimestamps();
    }

    /**
     * Get active accessories
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Get accessories by type for a product
     */
    public static function getByProductAndType($productId, $type)
    {
        return self::whereHas('products', function($query) use ($productId, $type) {
            $query->where('product_id', $productId)
                  ->where('accessory_type', $type);
        })->get();
    }
}
