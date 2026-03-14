@extends('layouts.app')

@section('title', 'Edit Product - Quotation App')

@section('styles')
<style>
    .form-container { max-width: 900px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px; }
    .form-group label span { color: #dc2626; }
    .form-control, .form-select { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .form-control:focus, .form-select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .btn-group { display: flex; gap: 12px; margin-top: 24px; }
    .section-title { font-size: 16px; font-weight: 600; color: #374151; margin: 24px 0 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .master-info { background: #f0fdf4; padding: 12px; border-radius: 6px; border-left: 4px solid #22c55e; margin-bottom: 16px; }
    .master-info p { margin: 4px 0; font-size: 13px; color: #374151; }
    
    @media (max-width: 600px) { 
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h1>Edit Product</h1>
            <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0 0;">Update product details and specifications</p>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h3 class="section-title">Master Product</h3>
                
                <div class="master-info">
                    <p><strong>Name:</strong> {{ $product->master->product_name }}</p>
                    <p><strong>Note:</strong> {{ $product->master->note ?? '-' }}</p>
                </div>

                <div class="form-group">
                    <label for="product_master_id">Product Master</label>
                    <select class="form-select" id="product_master_id" name="product_master_id" required>
                        @foreach($productMasters as $master)
                            <option value="{{ $master->id }}" {{ $product->product_master_id == $master->id ? 'selected' : '' }}>
                                {{ $master->product_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_master_id')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                </div>

                <h3 class="section-title">Product Details</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="product_model">Model/Variant <span>*</span></label>
                        <input type="text" class="form-control" id="product_model" name="product_model" 
                               value="{{ old('product_model', $product->product_model) }}" required placeholder="e.g. Model A">
                        @error('product_model')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Price <span>*</span></label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                               value="{{ old('price', $product->price) }}" required placeholder="0.00">
                        @error('price')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>
                </div>

                <h3 class="section-title">Specifications</h3>
                
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                    <div class="spec-header" style="display: grid; grid-template-columns: 2fr 2fr 1fr 60px; gap: 12px; margin-bottom: 12px; font-weight: 600; color: #374151; font-size: 13px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
                        <span>Specification Name</span>
                        <span>Value</span>
                        <span>Unit</span>
                        <span></span>
                    </div>

                    <div id="specs-container">
                        @forelse($allSpecs as $index => $spec)
                            <div class="spec-row" style="display: grid; grid-template-columns: 2fr 2fr 1fr 60px; gap: 12px; margin-bottom: 12px; align-items: end;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_name[]" 
                                           value="{{ old('spec_name.'.$index, $spec->spec_name) }}" placeholder="Specification Name" style="font-size: 13px;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_value[]" 
                                           value="{{ old('spec_value.'.$index, $spec->spec_value) }}" placeholder="Value" style="font-size: 13px;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_unit[]" 
                                           value="{{ old('spec_unit.'.$index, $spec->spec_unit) }}" placeholder="Unit" style="font-size: 13px;">
                                </div>
                                <div>
                                    <button type="button" class="btn-remove" onclick="removeSpecRow(this)">Remove</button>
                                </div>
                            </div>
                        @empty
                            <div class="spec-row" style="display: grid; grid-template-columns: 2fr 2fr 1fr 60px; gap: 12px; margin-bottom: 12px; align-items: end;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_name[]" placeholder="Specification Name" style="font-size: 13px;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_value[]" placeholder="Value" style="font-size: 13px;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" class="form-control" name="spec_unit[]" placeholder="Unit" style="font-size: 13px;">
                                </div>
                                <div>
                                    <button type="button" class="btn-remove" onclick="removeSpecRow(this)">Remove</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" class="btn-add" onclick="addSpecRow()" style="font-size: 13px; padding: 6px 12px;">+ Add Another Specification</button>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="{{ route('master.show', $product->product_master_id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addSpecRow() {
        const container = document.getElementById('specs-container');
        const rowCount = container.querySelectorAll('.spec-row').length;
        
        const newRow = document.createElement('div');
        newRow.className = 'spec-row';
        newRow.style.cssText = 'display: grid; grid-template-columns: 2fr 2fr 1fr 60px; gap: 12px; margin-bottom: 12px; align-items: end;';
        newRow.innerHTML = `
            <div class="form-group" style="margin-bottom: 0;">
                <input type="text" class="form-control" name="spec_name[]" placeholder="Specification Name" style="font-size: 13px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <input type="text" class="form-control" name="spec_value[]" placeholder="Value" style="font-size: 13px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <input type="text" class="form-control" name="spec_unit[]" placeholder="Unit" style="font-size: 13px;">
            </div>
            <div>
                <button type="button" class="btn-remove" onclick="removeSpecRow(this)">Remove</button>
            </div>
        `;
        container.appendChild(newRow);
    }

    function removeSpecRow(btn) {
        btn.closest('.spec-row').remove();
    }
</script>
@endsection