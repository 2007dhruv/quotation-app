@extends('layouts.app')

@section('title', 'Products - Quotation App')

@section('styles')
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    th { background: #f9fafb; font-weight: 600; color: #374151; }
    tr:hover { background: #f9fafb; }
    td { color: #6b7280; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; margin-bottom: 8px; color: #6b7280; }
    .specs { font-size: 12px; color: #9ca3af; margin-top: 4px; }
    .specs span { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; margin-right: 4px; display: inline-block; margin-bottom: 2px; }
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
<div class="card">
    <div class="card-header">
        <h1>Products</h1>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
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
                        <th>Product Name</th>
                        <th>Model</th>
                        <th>Price</th>
                        <th>Specifications</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="color: #374151; font-weight: 500;">{{ $product->product_name }}</td>
                            <td>{{ $product->product_type ?? '-' }}</td>
                            <td class="price">₹{{ number_format($product->default_price, 2) }}</td>
                            <td>
                                @if($product->specifications->count() > 0)
                                    <div class="specs">
                                        @foreach($product->specifications as $spec)
                                            <span>{{ $spec->spec_name }}: {{ $spec->spec_value }} {{ $spec->spec_unit }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">Edit</a>
                                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No products yet</h3>
                <p>Click the "Add Product" button to add your first product.</p>
            </div>
        @endif
    </div>
</div>
@endsection
