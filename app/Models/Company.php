<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
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
        'qr_code_path',
        'web_logo_path',
        'phone_icon_path',
        'mail_icon_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the primary company (active company)
     */
    public static function getPrimary()
    {
        return self::where('is_active', true)->first();
    }
}
