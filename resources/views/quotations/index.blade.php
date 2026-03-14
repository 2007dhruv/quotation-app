@extends('layouts.app')

@section('title', 'Quotations - Quotation App')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .quotations-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .quotations-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .quotations-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .quotations-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 24px;
        }

        .quotations-table {
            width: 100%;
            border-collapse: collapse;
        }

        .quotations-table thead {
            background: #f9fafb;
            color: #374151;
        }

        .quotations-table th {
            padding: 16px 12px;
            text-align: left;
            font-weight: 700;
            color: #374151;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: none !important;
        }

        .quotations-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .quotations-table tbody tr:hover {
            background: #f9fafb;
        }

        .quotations-table td {
            padding: 14px 12px;
            color: #6b7280;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .quotations-table td:first-child {
            color: #1f2937;
            font-weight: 600;
            width: 50px;
        }

        .quotation-number {
            color: #1f2937;
            font-weight: 600;
        }

        .total-amount {
            color: #1f2937;
            font-weight: 700;
            font-size: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-draft {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #78350f;
            border: 1px solid #fcd34d;
        }

        .status-sent {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .status-approved {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .action-buttons form {
            margin: 0;
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
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
            .quotations-header {
                flex-direction: column;
                align-items: flex-start;
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
    <div class="quotations-container">
        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="quotations-header">
            <h1>Quotations</h1>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('quotations.create') }}" class="btn btn-primary">+ Create Quotation</a>
                <a href="{{ route('quotations.trash') }}" class="btn btn-secondary"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; text-decoration: none;">🗑️
                    Trash</a>
            </div>
        </div>

        <div class="quotations-card">
            @if($quotations->count() > 0)
                <div class="table-responsive">
                    <table class="quotations-table" id="quotationsTable">
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
                                    <td class="quotation-number">{{ $quotation->quotation_number }}</td>
                                    <td>{{ $quotation->customer->customer_name }}</td>
                                    <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                                    <td class="total-amount">₹{{ number_format($quotation->total_amount, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $quotation->status }}">
                                            {{ ucfirst($quotation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('quotations.show', $quotation) }}" class="btn-sm btn-view">View</a>
                                            <a href="{{ route('quotations.pdf2', $quotation) }}" class="btn-sm btn-pdf">PDF</a>
                                            <form action="{{ route('quotations.destroy', $quotation) }}" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this quotation? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete"
                                                    style="border: none;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <h3>No quotations yet</h3>
                    <p>Click the "Create Quotation" button to create your first quotation.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#quotationsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                ordering: true,
                searching: true,
                info: true,
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ quotations",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next →",
                        previous: "← Previous"
                    },
                    emptyTable: "No quotations available"
                }
            });
        });
    </script>
@endsection