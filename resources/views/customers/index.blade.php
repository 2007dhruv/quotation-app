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
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Customers</h1>
        <div>
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
            <table>
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
        @else
            <div class="empty-state">
                <h3>No customers yet</h3>
                <p>Click the "Add Customer" button to add your first customer.</p>
            </div>
        @endif
    </div>
</div>
@endsection
