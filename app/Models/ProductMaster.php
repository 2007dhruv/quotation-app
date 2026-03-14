<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class ProductMaster extends Model
{
    protected $fillable = [
        'product_name',
        'product_image',
        'note',
        'standard_accessories',
        'optional_accessories',
        'specifications_template',
    ];

    protected $casts = [
        'standard_accessories' => 'json',
        'optional_accessories' => 'json',
        'specifications_template' => 'json',
    ];

    /**
     * Get all products (detailed specifications) for this master
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_master_id');
    }

    /**
     * Get standard accessories as array
     * Handles both single and double-encoded JSON data
     */
    public function getStandardAccessoriesArray()
    {
        $data = $this->standard_accessories;
        
        if (empty($data)) {
            return [];
        }
        
        // If it's already an array (properly decoded), return it
        if (is_array($data)) {
            return $data;
        }
        
        // If it's a string (double-encoded), decode it
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Get optional accessories as array
     * Handles both single and double-encoded JSON data
     */
    public function getOptionalAccessoriesArray()
    {
        $data = $this->optional_accessories;
        
        if (empty($data)) {
            return [];
        }
        
        // If it's already an array (properly decoded), return it
        if (is_array($data)) {
            return $data;
        }
        
        // If it's a string (double-encoded), decode it
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Set standard accessories
     */
    public function setStandardAccessories($accessories)
    {
        $this->standard_accessories = $accessories ? array_values(array_filter($accessories)) : null;
        return $this;
    }

    /**
     * Set optional accessories
     */
    public function setOptionalAccessories($accessories)
    {
        $this->optional_accessories = $accessories ? array_values(array_filter($accessories)) : null;
        return $this;
    }

    /**
     * Get specifications template as array
     */
    public function getSpecificationsTemplateArray()
    {
        $data = $this->specifications_template;
        
        if (empty($data)) {
            return [];
        }
        
        if (is_array($data)) {
            return $data;
        }
        
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Set specifications template
     */
    public function setSpecificationsTemplate($specNames)
    {
        if (is_array($specNames)) {
            // Filter out empty values and reset keys
            $filtered = array_filter($specNames, function($name) {
                return !empty(trim($name));
            });
            $this->specifications_template = count($filtered) > 0 ? array_values($filtered) : null;
        } else {
            $this->specifications_template = null;
        }
        return $this;
    }
}

