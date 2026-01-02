@extends('layouts.app')

@section('title', 'Edit Product - Quotation App')

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
    .image-preview { max-width: 200px; margin-top: 12px; border-radius: 6px; border: 1px solid #e5e7eb; padding: 8px; }
    
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
            <h1>Edit Product</h1>
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

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="product_name">Product Name <span>*</span></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="{{ old('product_name', $product->product_name) }}" required placeholder="Enter product name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="product_type">Product Type</label>
                        <input type="text" class="form-control" id="product_type" name="product_type" 
                               value="{{ old('product_type', $product->product_type) }}" placeholder="e.g. hydraulic_shearing">
                    </div>
                    <div class="form-group">
                        <label for="default_price">Default Price <span>*</span></label>
                        <input type="number" step="0.01" class="form-control" id="default_price" name="default_price" 
                               value="{{ old('default_price', $product->default_price) }}" required placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    @if($product->product_image)
                        <div class="image-preview">
                            <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" style="width: 100%; height: auto; border-radius: 4px;">
                            <small style="display: block; margin-top: 8px; color: #6b7280;">Current Image</small>
                        </div>
                    @else
                        <p style="color: #9ca3af; font-size: 14px;">No image uploaded</p>
                    @endif
                    <input type="file" class="form-control" id="product_image" name="product_image" 
                           accept="image/*" placeholder="Choose product image" style="margin-top: 12px;">
                    <small style="color: #6b7280;">Upload new image to replace current one (JPG, PNG, GIF, Max 2MB)</small>
                </div>

                <h3 class="section-title">Specifications</h3>
                
                <div class="spec-header">
                    <span>Spec Name</span>
                    <span>Value</span>
                    <span>Unit</span>
                </div>

                <div id="specs-container">
                    @forelse($product->specifications as $spec)
                        <div class="spec-row">
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_name[]" 
                                       value="{{ $spec->spec_name }}" placeholder="Cutting Length">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_value[]" 
                                       value="{{ $spec->spec_value }}" placeholder="2540">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_unit[]" 
                                       value="{{ $spec->spec_unit }}" placeholder="MM">
                            </div>
                        </div>
                    @empty
                        <div class="spec-row">
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_name[]" placeholder="Spec Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_value[]" placeholder="Value">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="spec_unit[]" placeholder="Unit">
                            </div>
                        </div>
                    @endforelse
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
                                $selectedStandard = $product->accessories()
                                    ->wherePivot('accessory_type', 'standard')
                                    ->pluck('accessories.id')
                                    ->toArray();
                            @endphp
                            @forelse($standardAccessories as $accessory)
                                <div class="checkbox-item">
                                    <input type="checkbox" id="std_acc_{{ $accessory->id }}" 
                                           name="standard_accessories[]" value="{{ $accessory->id }}"
                                           {{ in_array($accessory->id, $selectedStandard) ? 'checked' : '' }}>
                                    <label for="std_acc_{{ $accessory->id }}">{{ $accessory->name }}</label>
                                </div>
                            @empty
                                <p style="color: #9ca3af; font-size: 13px;">No accessories available</p>
                            @endforelse
                        </div>

                        <!-- Optional Accessories -->
                        <div class="accessory-group">
                            <h4>Optional Accessories</h4>
                            @php
                                $selectedOptional = $product->accessories()
                                    ->wherePivot('accessory_type', 'optional')
                                    ->pluck('accessories.id')
                                    ->toArray();
                            @endphp
                            @forelse($standardAccessories as $accessory)
                                <div class="checkbox-item">
                                    <input type="checkbox" id="opt_acc_{{ $accessory->id }}" 
                                           name="optional_accessories[]" value="{{ $accessory->id }}"
                                           {{ in_array($accessory->id, $selectedOptional) ? 'checked' : '' }}>
                                    <label for="opt_acc_{{ $accessory->id }}">{{ $accessory->name }}</label>
                                </div>
                            @empty
                                <p style="color: #9ca3af; font-size: 13px;">No accessories available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
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
