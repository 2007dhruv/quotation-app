@extends('layouts.app')

@section('title', 'Quotations Trash - Quotation App')

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

        .deleted-date {
            font-size: 12px;
            color: #9ca3af;
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

        .btn-restore {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-restore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-delete-permanent {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-delete-permanent:hover {
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

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fcd34d;
            color: #78350f;
        }

        .trash-info {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            color: #78350f;
            font-size: 14px;
        }

        .back-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

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

        <div class="trash-info">
            ⚠️ <strong>Trash Information:</strong> Deleted quotations appear here. You can restore them or permanently
            delete them. Permanently deleted items cannot be recovered.
        </div>

        <div class="quotations-header">
            <h1>🗑️ Quotations Trash</h1>

            <div style="display: flex; gap: 10px; align-items: center;">
                <div id="bulkActions" style="display: none; gap: 8px;">
                    <button type="button" class="btn-sm btn-restore" onclick="submitBulkAction('restore')">↩️ Restore
                        Selected</button>
                    <button type="button" class="btn-sm btn-delete-permanent" onclick="submitBulkAction('delete')">🗑️
                        Delete Selected Permanently</button>
                </div>
                <a href="{{ route('quotations.index') }}" class="back-link">← Back to Active Quotations</a>
            </div>
        </div>

        <div class="quotations-card">
            @if($quotations->count() > 0)
                <table class="quotations-table" id="quotationsTable">
                    <thead>
                        <tr>
                            <th width="5%"><input type="checkbox" id="selectAll" style="cursor: pointer;"></th>
                            <th>#</th>
                            <th>Quotation No</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Deleted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                            <tr>
                                <td><input type="checkbox" class="quotation-checkbox" value="{{ $quotation->id }}"
                                        style="cursor: pointer;"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td class="quotation-number">{{ $quotation->quotation_number }}</td>
                                <td>{{ $quotation->customer->customer_name }}</td>
                                <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                                <td class="total-amount">₹{{ number_format($quotation->total_amount, 2) }}</td>
                                <td class="deleted-date">{{ $quotation->deleted_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('quotations.restore', $quotation->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-restore">↩️ Restore</button>
                                        </form>
                                        <form action="{{ route('quotations.forceDelete', $quotation->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure? This will PERMANENTLY delete this quotation and cannot be undone!');\">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete-permanent">🗑️ Delete
                                                Permanently</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <h3>No deleted quotations</h3>
                    <p>Your trash is empty. All quotations here will be moved to trash when deleted.</p>
                </div>
            @endif
        </div>

        <!-- Hidden form for bulk actions -->
        <form id="bulkActionForm" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulkActionType">
            <div id="bulkActionIds"></div>
        </form>
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
                    info: "Showing _START_ to _END_ of _TOTAL_ deleted quotations",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next →",
                        previous: "← Previous"
                    },
                    emptyTable: "No deleted quotations available"
                },
                columnDefs: [
                    { orderable: false, targets: 0 } // Disable ordering on checkbox column
                ]
            });

            // Handle Select All checkbox
            $('#selectAll').on('change', function () {
                var isChecked = $(this).prop('checked');
                $('.quotation-checkbox').prop('checked', isChecked);
                toggleBulkActions();
            });

            // Handle individual checkboxes
            $('.quotation-table').on('change', '.quotation-checkbox', function () {
                toggleBulkActions();

                // Update select all state
                var allChecked = $('.quotation-checkbox:checked').length === $('.quotation-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Listen to change events dynamically (because DataTables changes the DOM)
            $(document).on('change', '.quotation-checkbox', function () {
                toggleBulkActions();
            });

        });

        function toggleBulkActions() {
            var checkedCount = $('.quotation-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#bulkActions').css('display', 'flex');
            } else {
                $('#bulkActions').hide();
            }
        }

        function submitBulkAction(action) {
            var selectedIds = [];
            $('.quotation-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one quotation.');
                return;
            }

            var confirmMessage = action === 'delete'
                ? 'Are you sure you want to PERMANENTLY delete the ' + selectedIds.length + ' selected quotation(s)? This cannot be undone!'
                : 'Are you sure you want to restore the ' + selectedIds.length + ' selected quotation(s)?';

            if (confirm(confirmMessage)) {
                var form = $('#bulkActionForm');

                // Set action URL based on action type
                if (action === 'delete') {
                    form.attr('action', '{{ route('quotations.bulkForceDelete') }}');
                    form.append('<input type="hidden" name="_method" value="DELETE">');
                } else {
                    form.attr('action', '{{ route('quotations.bulkRestore') }}');
                }

                // Add selected IDs
                $('#bulkActionIds').empty();
                selectedIds.forEach(function (id) {
                    $('#bulkActionIds').append('<input type="hidden" name="ids[]" value="' + id + '">');
                });

                form.submit();
            }
        }
    </script>
@endsection