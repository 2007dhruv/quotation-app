@extends('layouts.app')

@section('title', 'Products (Detailed) - Quotation App')

@section('styles')
<style>
    .header-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .header-row h1 { margin: 0; }
    .btn-group { display: flex; gap: 8px; }
    
    /* Navigation breadcrumb */
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
    .nav-breadcrumb a { color: #2563eb; text-decoration: none; font-weight: 500; }
    .nav-breadcrumb a:hover { text-decoration: underline; }
    .nav-breadcrumb span { color: #6b7280; }
    
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    th { background: #f9fafb; font-weight: 600; color: #374151; }
    tr:hover { background: #f9fafb; }
    td { color: #6b7280; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; margin-bottom: 8px; color: #6b7280; }
    .product-name { color: #374151; font-weight: 500; }
    .spec-badge { display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 4px; }
    .price { font-weight: 600; color: #059669; }
    .actions { display: flex; gap: 8px; }
    .btn-edit, .btn-delete { padding: 6px 12px; font-size: 12px; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-edit:hover { background: #2563eb; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-delete:hover { background: #dc2626; }
</style>
@endsection

@section('content')
<div class="nav-breadcrumb">
    <span>🔧 Product Models</span>
    <span>/</span>
    <a href="{{ route('products.create') }}">+ Add New Model</a>
    <span>|</span>
    <a href="{{ route('master.index') }}">← View Main Products</a>
</div>

<div class="card">
    <div class="card-header">
        <div class="header-row">
            <h1>Products (Detailed)</h1>
            <div class="btn-group">
                <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
                <a href="{{ route('master.index') }}" class="btn btn-secondary">View Masters</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($products->count() > 0)
            <table>
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
                    @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="product-name">{{ $product['master']['product_name'] ?? 'N/A' }}</td>
                            <td>{{ $product['product_model'] }}</td>
                            <td class="price">₹{{ number_format($product['price'], 2) }}</td>
                            <td>
                                @if(count($product['specs']) > 0)
                                    <div class="specs-list">
                                        @foreach($product['specs'] as $spec)
                                            <span class="spec-badge">{{ $spec['spec_name'] }}: {{ $spec['spec_value'] }} {{ $spec['spec_unit'] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-size: 12px;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('products.edit', $product['id']) }}" class="btn-edit">Edit</a>
                                    <form method="POST" action="{{ route('products.destroy', $product['id']) }}" style="display: inline; margin: 0;" id="delete-form-{{ $product['id'] }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-delete" onclick="confirmDelete('delete-form-{{ $product['id'] }}')">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No Products yet</h3>
                <p>Click the "Add Product" button to create your first detailed product.</p>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(formId) {
    if (confirm('Are you sure you want to delete this product and all its specifications?')) {
        document.getElementById(formId).submit();
    }
}
</script>
@endsection