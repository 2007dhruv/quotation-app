@extends('layouts.app')

@section('title', 'Customers - Quotation App')

@section('styles')
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    th { background: #f9fafb; font-weight: 600; color: #374151; }
    tr:hover { background: #f9fafb; }
    td { color: #6b7280; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; margin-bottom: 8px; color: #6b7280; }
    .action-buttons { display: flex; gap: 8px; align-items: center; }
    .btn-sm { padding: 6px 12px; font-size: 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-edit:hover { background: #2563eb; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-delete:hover { background: #dc2626; }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 16px; }
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { display: inline-block; margin: 0; font-size: 14px; color: #6b7280; }
    .dataTables_wrapper select,
    .dataTables_wrapper input { border-radius: 6px; border: 1px solid #d1d5db; padding: 8px 12px; font-size: 14px; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 20px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 8px; border: 1px solid #d1d5db; padding: 10px 16px; margin: 0 4px; cursor: pointer; background: white; color: #374151; font-size: 13px; font-weight: 600; transition: all 0.3s ease; min-width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f9fafb; color: white; border-color: #667eea; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-color: #667eea; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; background: #f3f4f6; color: #9ca3af; }
    .dataTables_wrapper .dataTables_paginate .paginate_button[aria-controls]:before { content: ""; margin-right: 0; }
    .dataTables_info { color: #6b7280; font-size: 14px; margin-top: 20px; padding: 12px; background: #f9fafb; border-radius: 8px; display: inline-block; }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Customers</h1>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('customers.export') }}" class="btn btn-secondary" style="background-color: #f8fafc; color: #059669; border: 1px solid #6ee7b7; display: flex; align-items: center; gap: 6px;" title="Export Customers to CSV">
                ⬇️ Export
            </a>

            <a href="{{ route('customers.import-form') }}" class="btn btn-secondary" style="background-color: #f8fafc; color: #2563eb; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 6px;" title="Import Customers from CSV">
                ⬆️ Import
            </a>
            <a href="{{ route('customers.trash') }}" class="btn btn-secondary" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 6px;">
                🗑️ Trash
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Add Customer</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="customers-table w-100" id="customersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>City</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>GST No</th>
                            <th>GST Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="color: #374151; font-weight: 500;">{{ $customer->customer_name }}</td>
                                <td>{{ $customer->city ?? '-' }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->gst_no ?? '-' }}</td>
                                <td>
                                    @if($customer->gst_type == 'instate')
                                        <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px;">In-State (18%)</span>
                                    @else
                                        <span style="background: #fecaca; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Out-of-State (18%)</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn-sm btn-edit">Edit</a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
            </div>
        @else
            <div class="empty-state">
                <h3>No customers yet</h3>
                <p>Click the "Add Customer" button to add your first customer.</p>
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
        $('#customersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            info: true,
            language: {
                search: "🔍 Search Customers:",
                lengthMenu: "Show _MENU_ customers",
                info: "Showing _START_ to _END_ of _TOTAL_ customers",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next →",
                    previous: "← Previous"
                }
            }
        });
    });
</script>
@endsection
