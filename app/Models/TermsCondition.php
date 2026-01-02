<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsCondition extends Model
{
    protected $table = 'terms_conditions';

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'display_order',
        'company_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Get active terms and conditions ordered by display_order
     */
    public static function getActive($company_id = null)
    {
        $query = self::where('is_active', true)
            ->orderBy('display_order', 'asc');
        
        if ($company_id) {
            $query->where('company_id', $company_id);
        }
        
        return $query->get();
    }

    /**
     * Relationship: Belongs to Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
