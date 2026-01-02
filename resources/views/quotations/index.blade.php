@extends('layouts.app')

@section('title', 'Quotations - Quotation App')

@section('styles')
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    th { background: #f9fafb; font-weight: 600; color: #374151; }
    tr:hover { background: #f9fafb; }
    td { color: #6b7280; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; margin-bottom: 8px; color: #6b7280; }
    .status { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
    .status-draft { background: #fef3c7; color: #92400e; }
    .status-sent { background: #dbeafe; color: #1e40af; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .actions { display: flex; gap: 8px; }
    
    /* Pagination Styles */
    .pagination-wrapper { margin-top: 24px; text-align: center; }
    .pagination { display: inline-flex; justify-content: center; gap: 4px; align-items: center; flex-wrap: wrap; }
    .pagination span { padding: 8px 12px; color: #6b7280; font-size: 13px; }
    .pagination a { 
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px; 
        border: 1px solid #d1d5db; 
        border-radius: 6px; 
        text-decoration: none; 
        color: #374151; 
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
        min-width: 36px;
        height: 36px;
    }
    .pagination a:hover { 
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .pagination a.active { 
        background: #2563eb; 
        color: #fff; 
        border-color: #2563eb;
    }
    .pagination a[rel="prev"], .pagination a[rel="next"] {
        min-width: auto;
        padding: 8px 12px;
    }
    .pagination svg { 
        width: 16px; 
        height: 16px;
        margin: 0 2px;
    }
    .pagination button { 
        padding: 8px 12px; 
        border: 1px solid #d1d5db; 
        border-radius: 6px; 
        background: #fff; 
        cursor: pointer; 
        color: #374151; 
        font-size: 14px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        height: 36px;
    }
    .pagination button:hover:not(:disabled) { 
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .pagination button:disabled { 
        opacity: 0.5; 
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Quotations</h1>
        <div>
            <a href="{{ route('quotations.create') }}" class="btn btn-primary">+ Create Quotation</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($quotations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quotation No</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotations as $quotation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="color: #374151; font-weight: 500;">{{ $quotation->quotation_number }}</td>
                            <td>{{ $quotation->customer->customer_name }}</td>
                            <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                            <td style="font-weight: 600;">₹{{ number_format($quotation->total_amount, 2) }}</td>
                            <td>
                                <span class="status status-{{ $quotation->status }}">
                                    {{ ucfirst($quotation->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-secondary btn-sm">View</a>
                                    <a href="{{ route('quotations.pdf2', $quotation) }}" class="btn btn-success btn-sm">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- <div class="pagination-wrapper">
                {{ $quotations->links() }}
            </div> -->
        @else
            <div class="empty-state">
                <h3>No quotations yet</h3>
                <p>Click the "Create Quotation" button to create your first quotation.</p>
            </div>
        @endif
    </div>
</div>
@endsection
