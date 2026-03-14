<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_short_name',
        'default_letter_body',
        'company_logo',
        'address',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'email',
        'website',
        'gst_number',
        'company_description',
        'bank_name',
        'bank_branch',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'account_type',
        'logo_path',
        'signature_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot function to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When a company is being saved
        static::saving(function ($company) {
            // If this company is being set to active
            if ($company->is_active === true || $company->is_active === 1) {
                // Deactivate all other companies
                static::where('id', '!=', $company->id)->update(['is_active' => false]);
            }
        });
    }

    /**
     * Get the primary company (active company)
     */
    public static function getPrimary()
    {
        return self::where('is_active', true)->first();
    }
}
