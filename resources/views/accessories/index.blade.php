@extends('layouts.app')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
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
    
    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }
    
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .card-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .card-table thead {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-bottom: 2px solid #d1d5db;
    }
    
    .card-table th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .card-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .card-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .card-table td {
        padding: 16px;
        font-size: 14px;
        color: #374151;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-active {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .badge-inactive {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .btn-edit:hover {
        background: #dbeafe;
        border-color: #2563eb;
    }
    
    .btn-delete {
        color: #dc2626;
        background: none;
        border: 1px solid transparent;
        font-weight: 600;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-delete:hover {
        background: #fee2e2;
        border-color: #dc2626;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6b7280;
    }
    
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        opacity: 0.3;
    }
    
    .empty-state p {
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .empty-state a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    
    .empty-state a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 36px; height: 36px; color: #2563eb;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        Accessories Management
    </h1>
    <a href="{{ route('accessories.create') }}" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add New Accessory
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        <strong>✓ Success!</strong> {{ session('success') }}
    </div>
@endif

<div class="card">
    @if($accessories->count() > 0)
        <table class="card-table">
            <thead>
                <tr>
                    <th width="30%">Name</th>
                    <th width="30%">Description</th>
                    <th width="20%">Notes</th>
                    <th width="10%">Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accessories as $accessory)
                    <tr>
                        <td>
                            <strong style="color: #1f2937;">{{ $accessory->name }}</strong>
                        </td>
                        <td>
                            <span style="color: #6b7280;">{{ $accessory->description ? Str::limit($accessory->description, 40) : '-' }}</span>
                        </td>
                        <td>
                            <span style="color: #6b7280; font-size: 12px;">{{ $accessory->notes ? Str::limit($accessory->notes, 35) : '-' }}</span>
                        </td>
                        <td>
                            @if($accessory->is_active)
                                <span class="badge badge-active">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="badge badge-inactive">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('accessories.edit', $accessory->id) }}" class="btn-edit">Edit</a>
                                <form method="POST" action="{{ route('accessories.destroy', $accessory->id) }}" style="margin: 0; display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this accessory?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p>No accessories found yet.</p>
            <a href="{{ route('accessories.create') }}">Create the first one now →</a>
        </div>
    @endif
</div>
@endsection
