<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'product_master_id',
        'product_model',
        'spec_name',
        'spec_value',
        'spec_unit',
        'price',
    ];

    /**
     * Get the product master this product belongs to
     */
    public function master(): BelongsTo
    {
        return $this->belongsTo(ProductMaster::class, 'product_master_id');
    }

    /**
     * Get product name from master
     */
    public function getProductNameAttribute()
    {
        return $this->master?->product_name;
    }

    /**
     * Get product image from master
     */
    public function getProductImageAttribute()
    {
        return $this->master?->product_image;
    }

    /**
     * Get note from master
     */
    public function getNoteAttribute()
    {
        return $this->master?->note;
    }

    /**
     * Accessor for backward compatibility - default_price maps to price
     */
    public function getDefaultPriceAttribute()
    {
        return $this->attributes['price'] ?? 0;
    }
}
