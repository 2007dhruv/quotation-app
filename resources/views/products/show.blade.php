@extends('layouts.app')

@section('title', $productMaster->product_name . ' - Master Details - Quotation App')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        /* Breadcrumb */
        .nav-breadcrumb {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-breadcrumb a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-breadcrumb a:hover {
            text-decoration: underline;
        }

        .nav-breadcrumb span {
            color: #6b7280;
        }

        /* Master info card */
        .master-info {
            background: #f9fafb;
            color: white;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            color: #374151;
        }

        .master-info-item {}

        .master-info-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .master-info-value {
            font-size: 16px;
            font-weight: 600;
        }

        .master-image {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
        }

        /* Models section */
        .models-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 24px 0 16px 0;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .models-header h2 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }

        /* Models card */
        .models-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 24px;
        }

        /* Models table */
        .models-table {
            width: 100%;
            border-collapse: collapse;
        }

        .models-table thead {
            background: #f9fafb;
        }

        .models-table th {
            padding: 16px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            color: #383838 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none !important;
        }

        .models-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s;
        }

        .models-table tbody tr:hover {
            background: #f9fafb;
        }

        .models-table td {
            padding: 14px 12px;
            font-size: 14px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .models-table td:first-child {
            color: #1f2937;
            font-weight: 600;
            width: 50px;
        }

        .price-cell {
            color: #059669 !important;
            font-weight: 700 !important;
            font-size: 15px !important;
        }

        /* Accessories section */
        .accessories-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .accessories-group {
            background: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .accessories-group h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .accessories-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .accessories-list li {
            padding: 8px 0;
            color: #6b7280;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }

        .accessories-list li:last-child {
            border-bottom: none;
        }

        .accessories-list li::before {
            content: '• ';
            color: #667eea;
            font-weight: bold;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .empty-state h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #6b7280;
        }

        /* Action buttons */
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

        .spec-badge {
            display: inline-block;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            padding: 6px 10px;
            border-radius: 16px;
            font-size: 12px;
            white-space: nowrap;
            border: 1px solid #93c5fd;
            font-weight: 600;
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

        /* Responsive */
        @media (max-width: 768px) {
            .master-info {
                grid-template-columns: 1fr;
            }

            .accessories-section {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-sm {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="nav-breadcrumb">
        <a href="{{ route('master.index') }}">📦 Product Masters</a>
        <span>/</span>
        <span>{{ $productMaster->product_name }}</span>
    </div>

    <!-- Master Info Card -->
    <div class="master-info">
        <div>
            <div class="master-info-item">
                <div class="master-info-label">Product Name</div>
                <div class="master-info-value">{{ $productMaster->product_name }}</div>
            </div>
            <div class="master-info-item" style="margin-top: 16px;">
                <div class="master-info-label">Models Available</div>
                <div class="master-info-value">{{ $productMaster->products->unique('product_model')->count() }} Models</div>
            </div>
        </div>
        <div>
            @if($productMaster->product_image)
                @php
                    $imagePath = $productMaster->product_image;
                    if (strpos($imagePath, 'storage/') === 0) {
                        $imagePath = substr($imagePath, 8);
                    }
                @endphp
                <img src="{{ route('storage.file', ['path' => $imagePath]) }}" alt="{{ $productMaster->product_name }}"
                    class="master-image">
            @else
                <div style="opacity: 0.5; text-align: center; padding: 20px;">
                    📷 No Image
                </div>
            @endif
        </div>
    </div>

    <!-- Models Section -->
    <div class="models-header">
        <h2>📋 Product Models ({{ $productMaster->products->unique('product_model')->count() }})</h2>
        <a href="{{ route('products.create', ['master_id' => $productMaster->id]) }}" class="btn-sm btn-edit">+ Add
            Model</a>
    </div>

    <div class="models-card">
        @if($productMaster->products->count() > 0)
            <table class="models-table" id="modelsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Master Product</th>
                        <th>Model</th>
                        <th>Price</th>
                        <th>Specifications</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Get unique models with their first product record
                        $uniqueModels = $productMaster->products->unique('product_model')->values();
                    @endphp
                    @foreach($uniqueModels as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight: 600; color: #1f2937;">{{ $productMaster->product_name }}</td>
                            <td style="font-weight: 600;">{{ $product->product_model }}</td>
                            <td class="price-cell">₹{{ number_format($product->default_price, 2) }}</td>
                            <td>
                                @php
                                    $uniqueSpecs = $productMaster->products
                                        ->where('product_model', $product->product_model)
                                        ->where('product_master_id', $productMaster->id)
                                        ->filter(fn($p) => $p->spec_name)
                                        ->unique(fn($p) => $p->spec_name . '|' . $p->spec_value)
                                        ->values();
                                @endphp
                                @if($uniqueSpecs->count() > 0)
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($uniqueSpecs as $spec)
                                            <span class="spec-badge">
                                                {{ $spec->spec_name }}:
                                                {{ $spec->spec_value }}{{ $spec->spec_unit ? ' ' . $spec->spec_unit : '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-size: 12px;">No specifications</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-sm btn-edit">Edit</a>
                                    <form method="POST"
                                        action="{{ route('products.destroy-model', [$productMaster->id, $product->product_model]) }}"
                                        style="display: inline; margin: 0;" class="delete-form"
                                        data-model-name="{{ $product->product_model }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-sm btn-delete delete-btn"
                                        data-model-name="{{ $product->product_model }}">Delete Model</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No Models yet</h3>
                <p>Click the "Add New Model" button to create your first model for this master.</p>
            </div>
        @endif
    </div>

    <!-- Accessories Section -->
    @php
        $stdAccessories = $productMaster->getStandardAccessoriesArray();
        $optAccessories = $productMaster->getOptionalAccessoriesArray();
    @endphp

    @if(!empty($stdAccessories) || !empty($optAccessories))
        <div class="accessories-section">
            @if(!empty($stdAccessories))
                <div class="accessories-group">
                    <h3>✓ Standard Accessories</h3>
                    <ul class="accessories-list">
                        @foreach($stdAccessories as $acc)
                            <li>{{ $acc }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!empty($optAccessories))
                <div class="accessories-group">
                    <h3>◇ Optional Accessories</h3>
                    <ul class="accessories-list">
                        @foreach($optAccessories as $acc)
                            <li>{{ $acc }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#modelsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                ordering: true,
                searching: true,
                info: true,
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ models",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next →",
                        previous: "← Previous"
                    },
                    emptyTable: "No models available"
                }
            });

            // Handle delete button click with event delegation
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                const modelName = $(this).data('model-name');
                const form = $(this).closest('td').find('.delete-form[data-model-name="' + modelName + '"]');

                if (confirm('Are you sure you want to delete the entire model "' + modelName + '" and all its specifications?')) {
                    console.log('Deleting model:', modelName);
                    form.submit();
                }
            });
        });
    </script>

@endsection