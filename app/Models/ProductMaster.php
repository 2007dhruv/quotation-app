<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductMaster extends Model
{
    protected $fillable = [
        'product_name',
        'product_type',
        'default_price',
        'product_image',
    ];

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'product_id');
    }

    /**
     * Get all accessories for this product
     */
    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(Accessory::class, 'product_accessories', 'product_id', 'accessory_id')
                    ->withPivot('accessory_type')
                    ->withTimestamps();
    }

    /**
     * Get standard accessories only
     */
    public function standardAccessories()
    {
        return $this->accessories()
                    ->where('accessory_type', 'standard');
    }

    /**
     * Get optional accessories only
     */
    public function optionalAccessories()
    {
        return $this->accessories()
                    ->where('accessory_type', 'optional');
    }
}

