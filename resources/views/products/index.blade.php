@extends('layouts.app')

@section('title', 'Product Masters - Quotation App')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .products-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .products-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-add {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .products-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        padding: 24px;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead {
        background: #f9fafb; 
        color: #374151;
    }

    .products-table th {
        padding: 16px 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #374151 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none !important;
    }

    .products-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .products-table tbody tr:hover {
        background: #f9fafb;
    }

    .products-table td {
        padding: 14px 12px;
        font-size: 14px;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .products-table td:first-child {
        color: #1f2937;
        font-weight: 600;
        width: 50px;
    }

    .product-name {
        color: #1f2937;
        font-weight: 600;
    }

    .model-badge {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #6ee7b7;
    }

    .accessories-badge {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        margin-right: 4px;
        font-weight: 600;
        border: 1px solid #93c5fd;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 7px 14px;
        font-size: 12px;
        text-decoration: none;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-view {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #6b7280;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .alert {
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 16px;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        display: inline-block;
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }

    .dataTables_wrapper select,
    .dataTables_wrapper input {
        border-radius: 6px;
        border: 1px solid #d1d5db;
        padding: 8px 12px;
        font-size: 14px;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 20px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 10px 16px;
        margin: 0 4px;
        cursor: pointer;
        background: white;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
        background: #f9fafb;
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f3f4f6;
        color: #9ca3af;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button[aria-controls]:before {
        content: "";
        margin-right: 0;
    }

    .dataTables_info {
        color: #6b7280;
        font-size: 14px;
        margin-top: 20px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .products-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }

        .btn-sm {
            width: 100%;
            text-align: center;
            padding: 6px 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="products-container">
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="products-header">
        <h1>Product Masters</h1>
        <div class="btn-group">
            <a href="{{ route('master.import-form') }}" class="btn-add">📥 Import CSV</a>
            <a href="{{ route('master.create') }}" class="btn-add">+ Add Master</a>
        </div>
    </div>

    <div class="products-card">
        @if($productMasters->count() > 0)
            <table class="products-table" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Models</th>
                        <th>Accessories</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productMasters as $master)
                        <tr id="product-{{ $master->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="product-name">{{ $master->product_name }}</td>
                            <td>
                                <span class="model-badge">{{ $master->products->unique('product_model')->count() }} models</span>
                            </td>
                            <td>
                                @php
                                    $stdCount = count($master->getStandardAccessoriesArray());
                                    $optCount = count($master->getOptionalAccessoriesArray());
                                @endphp
                                @if($stdCount > 0 || $optCount > 0)
                                    <div>
                                        @if($stdCount > 0)
                                            <span class="accessories-badge">✓ {{ $stdCount }} std</span>
                                        @endif
                                        @if($optCount > 0)
                                            <span class="accessories-badge">◇ {{ $optCount }} opt</span>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-size: 12px;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('master.show', $master->id) }}" class="btn-sm btn-view">View</a>
                                    <a href="{{ route('master.edit', $master->id) }}" class="btn-sm btn-edit">Edit</a>
                                    <form method="POST" action="{{ route('master.destroy', $master->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this master?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No Product Masters yet</h3>
                <p>Click the "Add Master" button to create your first product master.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            ordering: true,
            searching: true,
            info: true,
            language: {
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ products",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next →",
                    previous: "← Previous"
                },
                emptyTable: "No products available"
            }
        });
    });
</script>
@endsection
