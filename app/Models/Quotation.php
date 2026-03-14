<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'quotation_number',
        'company_id',
        'customer_id',
        'quotation_date',
        'valid_until',
        'quotation_letter_body',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'discount_percent',
        'discount_amount',
        'total_amount',
        'notes',
        'status',
        'subject',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function termsConditions(): BelongsToMany
    {
        return $this->belongsToMany(
            TermsCondition::class,
            'quotation_terms_conditions',
            'quotation_id',
            'terms_condition_id'
        );
    }

    public static function generateQuotationNumber($companyId = null): string
    {
        // If company_id provided, use that; otherwise use active company
        if ($companyId) {
            $company = Company::findOrFail($companyId);
            $prefix = strtoupper($company->company_short_name);
        } else {
            $activeCompany = Company::where('is_active', true)->first();
            $prefix = $activeCompany ? strtoupper($activeCompany->company_short_name) : 'AMT';
        }

        $month = date('m');
        $year = date('Y');

        // Get ALL active quotations for current month/year with this company prefix
        // We need to check the actual sequence number, not just the most recent by creation date
        $pattern = $prefix . '/' . $month . '/' . $year . '/%';
        $quotations = self::where('quotation_number', 'like', $pattern)
            ->whereNull('deleted_at')
            ->get(['quotation_number']);

        // Find the highest sequence number
        $maxSequence = 0;
        foreach ($quotations as $q) {
            $sequence = intval(substr($q->quotation_number, -4));
            if ($sequence > $maxSequence) {
                $maxSequence = $sequence;
            }
        }

        // Next sequence is max + 1
        $nextSequence = $maxSequence + 1;
        return $prefix . '/' . $month . '/' . $year . '/' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }
}
