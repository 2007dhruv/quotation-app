<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'customer_id',
        'quotation_date',
        'valid_until',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'discount_percent',
        'discount_amount',
        'total_amount',
        'notes',
        'status',
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

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public static function generateQuotationNumber(): string
    {
        $prefix = 'QT';
        $date = date('Ymd');
        $lastQuotation = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastQuotation ? (intval(substr($lastQuotation->quotation_number, -4)) + 1) : 1;
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
