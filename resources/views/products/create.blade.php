@extends('layouts.app')

@section('title', 'Add Product - Quotation App')

@section('styles')
<style>
    .form-container { max-width: 700px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px; }
    .form-group label span { color: #dc2626; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .btn-group { display: flex; gap: 12px; margin-top: 24px; }
    .section-title { font-size: 16px; font-weight: 600; color: #374151; margin: 24px 0 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .spec-row { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px; align-items: end; }
    .spec-row .form-group { margin-bottom: 0; }
    .spec-header { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 8px; }
    .spec-header span { font-size: 13px; font-weight: 500; color: #6b7280; }
    #specs-container { margin-bottom: 16px; }
    
    /* Accessories styling */
    .accessories-section { margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .accessories-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .accessory-group { background: #f9fafb; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb; }
    .accessory-group h4 { margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1f2937; }
    .checkbox-item { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .checkbox-item input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
    .checkbox-item label { margin: 0; cursor: pointer; font-size: 14px; color: #374151; }
    
    @media (max-width: 600px) { 
        .form-row { grid-template-columns: 1fr; } 
        .spec-row { grid-template-columns: 1fr; }
        .spec-header { display: none; }
        .accessories-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h1>Add New Product</h1>
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

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="product_name">Product Name <span>*</span></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="{{ old('product_name') }}" required placeholder="Enter product name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="product_type">Product Type</label>
                        <input type="text" class="form-control" id="product_type" name="product_type" 
                               value="{{ old('product_type') }}" placeholder="e.g. hydraulic_shearing">
                    </div>
                    <div class="form-group">
                        <label for="default_price">Default Price <span>*</span></label>
                        <input type="number" step="0.01" class="form-control" id="default_price" name="default_price" 
                               value="{{ old('default_price') }}" required placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    <input type="file" class="form-control" id="product_image" name="product_image" 
                           accept="image/*" placeholder="Choose product image">
                    <small style="color: #6b7280;">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                </div>

                <h3 class="section-title">Specifications</h3>
                
                <div class="spec-header">
                    <span>Spec Name</span>
                    <span>Value</span>
                    <span>Unit</span>
                </div>

                <div id="specs-container">
                    <div class="spec-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_name[]" placeholder="Cutting Length">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_value[]" placeholder="2540">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_unit[]" placeholder="MM">
                        </div>
                    </div>
                    <div class="spec-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_name[]" placeholder="Power">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_value[]" placeholder="5">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_unit[]" placeholder="HP">
                        </div>
                    </div>
                    <div class="spec-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_name[]" placeholder="Overall Length">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_value[]" placeholder="3200">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="spec_unit[]" placeholder="MM">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success" onclick="addSpecRow()">+ Add Specification</button>

                <div class="accessories-section">
                    <h3 class="section-title">Product Accessories</h3>
                    
                    <div class="accessories-grid">
                        <!-- Standard Accessories -->
                        <div class="accessory-group">
                            <h4>Standard Accessories</h4>
                            @php
                                $standardAccessories = \App\Models\Accessory::where('is_active', true)->orderBy('name')->get();
                            @endphp
                            @forelse($standardAccessories as $accessory)
                                <div class="checkbox-item">
                                    <input type="checkbox" id="std_acc_{{ $accessory->id }}" 
                                           name="standard_accessories[]" value="{{ $accessory->id }}">
                                    <label for="std_acc_{{ $accessory->id }}">{{ $accessory->name }}</label>
                                </div>
                            @empty
                                <p style="color: #9ca3af; font-size: 13px;">No accessories available</p>
                            @endforelse
                        </div>

                        <!-- Optional Accessories -->
                        <div class="accessory-group">
                            <h4>Optional Accessories</h4>
                            @forelse($standardAccessories as $accessory)
                                <div class="checkbox-item">
                                    <input type="checkbox" id="opt_acc_{{ $accessory->id }}" 
                                           name="optional_accessories[]" value="{{ $accessory->id }}">
                                    <label for="opt_acc_{{ $accessory->id }}">{{ $accessory->name }}</label>
                                </div>
                            @empty
                                <p style="color: #9ca3af; font-size: 13px;">No accessories available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Save Product</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function addSpecRow() {
        const container = document.getElementById('specs-container');
        const row = document.createElement('div');
        row.className = 'spec-row';
        row.innerHTML = `
            <div class="form-group">
                <input type="text" class="form-control" name="spec_name[]" placeholder="Spec Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="spec_value[]" placeholder="Value">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="spec_unit[]" placeholder="Unit">
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endsection
