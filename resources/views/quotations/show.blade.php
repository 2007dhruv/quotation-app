@extends('layouts.app')

@section('title', 'Quotation - ' . $quotation->quotation_number)

@section('styles')
<style>
    .header-info { display: flex; justify-content: space-between; margin-bottom: 24px; }
    .header-info h2 { color: #2563eb; font-size: 24px; margin-bottom: 8px; }
    .header-info .quotation-number { font-size: 14px; color: #6b7280; }
    .header-info .date-info { text-align: right; }
    .header-info .date-info p { font-size: 14px; color: #6b7280; margin: 4px 0; }
    
    .customer-info { background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
    .customer-info h3 { color: #374151; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
    .customer-info h4 { color: #111827; font-size: 18px; margin-bottom: 8px; }
    .customer-info p { color: #6b7280; font-size: 14px; margin: 4px 0; }
    
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .items-table th, .items-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    .items-table th { background: #f9fafb; font-weight: 600; font-size: 13px; color: #374151; }
    .items-table td { font-size: 14px; color: #374151; }
    .items-table .text-right { text-align: right; }
    
    .totals-section { display: flex; justify-content: flex-end; margin-bottom: 24px; }
    .totals-box { width: 300px; }
    .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #374151; }
    .totals-row.total { border-top: 2px solid #e5e7eb; margin-top: 8px; padding-top: 16px; font-weight: 600; font-size: 18px; color: #111827; }
    
    .notes-section { background: #fffbeb; padding: 16px; border-radius: 8px; border-left: 4px solid #f59e0b; }
    .notes-section h4 { color: #92400e; font-size: 14px; margin-bottom: 8px; }
    .notes-section p { color: #78350f; font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
    
    .actions { display: flex; gap: 12px; }
    
    .status { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 500; margin-left: 12px; }
    .status-draft { background: #fef3c7; color: #92400e; }
    .status-sent { background: #dbeafe; color: #1e40af; }
    .status-approved { background: #d1fae5; color: #065f46; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h1>Quotation Details</h1>
        </div>
        <div class="actions">
             <a href="{{ route('quotations.index') }}" class="btn btn-secondary">← Back to List</a>
            <!--<a href="{{ route('quotations.pdf.stream', $quotation) }}" class="btn btn-primary" target="_blank">Short pdf</a>
            <a href="{{ route('quotations.pdf', $quotation) }}" class="btn btn-success">Download pdf</a> -->
            <a href="{{ route('quotations.pdf2.stream', $quotation) }}" class="btn btn-primary" target="_blank">View pdf</a>
            <a href="{{ route('quotations.pdf2', $quotation) }}" class="btn btn-success">Download pdf</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Info -->
        <div class="header-info">
            <div>
                <h2>QUOTATION <span class="status status-{{ $quotation->status }}">{{ ucfirst($quotation->status) }}</span></h2>
                <p class="quotation-number">#{{ $quotation->quotation_number }}</p>
            </div>
            <div class="date-info">
                <p><strong>Date:</strong> {{ $quotation->quotation_date->format('d M Y') }}</p>
                @if($quotation->valid_until)
                    <p><strong>Valid Until:</strong> {{ $quotation->valid_until->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <h3>Bill To</h3>
            <h4>{{ $quotation->customer->customer_name }}</h4>
            @if($quotation->customer->address)
                <p>{{ $quotation->customer->address }}</p>
            @endif
            @if($quotation->customer->city || $quotation->customer->state)
                <p>{{ $quotation->customer->city }}{{ $quotation->customer->city && $quotation->customer->state ? ', ' : '' }}{{ $quotation->customer->state }}</p>
            @endif
            @if($quotation->customer->mobile)
                <p>Mobile: {{ $quotation->customer->mobile }}</p>
            @endif
            @if($quotation->customer->email)
                <p>Email: {{ $quotation->customer->email }}</p>
            @endif
            @if($quotation->customer->gst_no)
                <p>GST: {{ $quotation->customer->gst_no }}</p>
            @endif
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="40%">Product</th>
                    <th width="15%">Type</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->description)
                                <br><small style="color: #6b7280;">{{ $item->description }}</small>
                            @endif
                        </td>
                        <td>{{ $item->product_type ?? 'N/A' }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                @if($quotation->discount_percent > 0)
                    <div class="totals-row">
                        <span>Discount ({{ $quotation->discount_percent }}%):</span>
                        <span>-₹{{ number_format($quotation->subtotal * ($quotation->discount_percent / 100), 2) }}</span>
                    </div>
                @endif
                <div class="totals-row total">
                    <span>Grand Total:</span>
                    <span>₹{{ number_format($quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <!-- @if($quotation->notes)
            <div class="notes-section">
                <h4>Notes / Terms & Conditions</h4>
                <p>{{ $quotation->notes }}</p>
            </div>
        @endif -->
    </div>
</div>
@endsection
