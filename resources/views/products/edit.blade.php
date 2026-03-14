@extends('layouts.app')

@section('title', 'Edit Product Master - Quotation App')

@section('styles')
    <style>
        .form-container {
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .form-group label span {
            color: #dc2626;
        }

        .form-control,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin: 24px 0 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .image-preview {
            max-width: 200px;
            margin-top: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        /* Accessories styling */
        .accessories-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .accessory-group {
            background: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .accessory-group h4 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .accessory-item {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 10px;
        }

        .accessory-item input {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }

        .accessory-item input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .btn-remove-accessory {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
        }

        .btn-remove-accessory:hover {
            background: #dc2626;
        }

        .btn-add-row {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
            width: 100%;
        }

        .btn-add-row:hover {
            background: #2563eb;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .accessories-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="form-container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Product Master</h1>
                <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0 0;">Update basic details, image, and accessories
                </p>
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

                <form action="{{ route('master.update', $productMaster->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3 class="section-title">Basic Information</h3>

                    <div class="form-group">
                        <label for="product_name">Product Name <span>*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            value="{{ old('product_name', $productMaster->product_name) }}" required
                            placeholder="e.g. Hydraulic Shearing Machine">
                        @error('product_name')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-group">
                        <label for="product_image">Product Image</label>
                        @if($productMaster->product_image)
                            <div class="image-preview">
                                @php
                                    // Handle both path formats: 'products/file.png' and 'storage/products/file.png'
                                    $imagePath = $productMaster->product_image;
                                    if (strpos($imagePath, 'storage/') === 0) {
                                        $imagePath = substr($imagePath, 8); // Remove 'storage/' prefix
                                    }
                                @endphp
                                <img src="{{ route('storage.file', ['path' => $imagePath]) }}"
                                    alt="{{ $productMaster->product_name }}"
                                    style="width: 100%; height: auto; border-radius: 4px;">
                                <small style="display: block; margin-top: 8px; color: #6b7280;">Current Image</small>
                            </div>
                        @else
                            <p style="color: #9ca3af; font-size: 14px;">No image uploaded</p>
                        @endif
                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*"
                            style="margin-top: 12px;">
                        <small style="color: #6b7280;">Upload new image to replace current one (JPG, PNG, GIF, WEBP, Max
                            10MB)</small>
                        @error('product_image')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-group">
                        <label for="note">Product Note</label>
                        <textarea class="form-control" id="note" name="note" rows="4"
                            placeholder="Add any additional notes about this product...">{{ old('note', $productMaster->note) }}</textarea>
                        @error('note')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <h3 class="section-title">Accessories</h3>

                    <div class="accessories-grid">
                        <!-- Standard Accessories -->
                        <div class="accessory-group">
                            <h4>✓ Standard Accessories</h4>
                            <div id="standard-accessories-container">
                                @forelse($productMaster->getStandardAccessoriesArray() ?? [] as $accessory)
                                    <div class="accessory-item">
                                        <input type="text" name="standard_accessories[]" placeholder="e.g. Hydraulic Pump"
                                            value="{{ $accessory }}">
                                        <button type="button" class="btn-remove-accessory"
                                            onclick="removeAccessoryRow(this)">Remove</button>
                                    </div>
                                @empty
                                    <div class="accessory-item">
                                        <input type="text" name="standard_accessories[]" placeholder="e.g. Hydraulic Pump"
                                            value="">
                                        <button type="button" class="btn-remove-accessory"
                                            onclick="removeAccessoryRow(this)">Remove</button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn-add-row" onclick="addAccessoryRow('standard')">+ Add Standard
                                Accessory</button>
                        </div>

                        <!-- Optional Accessories -->
                        <div class="accessory-group">
                            <h4>◇ Optional Accessories</h4>
                            <div id="optional-accessories-container">
                                @forelse($productMaster->getOptionalAccessoriesArray() ?? [] as $accessory)
                                    <div class="accessory-item">
                                        <input type="text" name="optional_accessories[]" placeholder="e.g. Digital Display"
                                            value="{{ $accessory }}">
                                        <button type="button" class="btn-remove-accessory"
                                            onclick="removeAccessoryRow(this)">Remove</button>
                                    </div>
                                @empty
                                    <div class="accessory-item">
                                        <input type="text" name="optional_accessories[]" placeholder="e.g. Digital Display"
                                            value="">
                                        <button type="button" class="btn-remove-accessory"
                                            onclick="removeAccessoryRow(this)">Remove</button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn-add-row" onclick="addAccessoryRow('optional')">+ Add Optional
                                Accessory</button>
                        </div>
                    </div>

                    <!-- SPECIFICATIONS TEMPLATE -->
                    <div class="section-container">
                        <h3 class="section-title">📋 Default Specifications Template</h3>
                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 12px;">Define specification names AND
                            units here. Users will auto-populate these when creating product models.</p>

                        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; margin-bottom: 12px;">
                            <div style="font-weight: 500; color: #6b7280; font-size: 13px;">Specification Name</div>
                            <div style="font-weight: 500; color: #6b7280; font-size: 13px;">Unit (e.g. MM, KG, HP)</div>
                            <div></div>
                        </div>

                        <div id="template-container">
                            @forelse($productMaster->getSpecificationsTemplateArray() ?? [] as $index => $spec)
                                <div class="template-item"
                                    style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: center; margin-bottom: 12px;">
                                    <input type="text" name="specifications_template[{{ $index }}][name]"
                                        placeholder="e.g. Cutting Length" value="{{ $spec['name'] ?? '' }}"
                                        class="form-control">
                                    <input type="text" name="specifications_template[{{ $index }}][unit]" placeholder="e.g. MM"
                                        value="{{ $spec['unit'] ?? '' }}" class="form-control">
                                    <button type="button" class="btn-remove-template"
                                        onclick="removeTemplateRow(this)">Remove</button>
                                </div>
                            @empty
                                <div class="template-item"
                                    style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: center; margin-bottom: 12px;">
                                    <input type="text" name="specifications_template[0][name]" placeholder="e.g. Cutting Length"
                                        value="" class="form-control">
                                    <input type="text" name="specifications_template[0][unit]" placeholder="e.g. MM" value=""
                                        class="form-control">
                                    <button type="button" class="btn-remove-template"
                                        onclick="removeTemplateRow(this)">Remove</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn-add-row" onclick="addTemplateRow()">+ Add Specification</button>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Update Product Master</button>
                        <a href="{{ route('master.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addAccessoryRow(type) {
            const containerId = type === 'standard' ? 'standard-accessories-container' : 'optional-accessories-container';
            const fieldName = type === 'standard' ? 'standard_accessories[]' : 'optional_accessories[]';
            const placeholder = type === 'standard' ? 'e.g. Hydraulic Pump' : 'e.g. Digital Display';
            const container = document.getElementById(containerId);

            const newRow = document.createElement('div');
            newRow.className = 'accessory-item';
            newRow.innerHTML = `
                <input type="text" name="${fieldName}" placeholder="${placeholder}" value="">
                <button type="button" class="btn-remove-accessory" onclick="removeAccessoryRow(this)">Remove</button>
            `;
            container.appendChild(newRow);
        }

        function removeAccessoryRow(button) {
            button.parentElement.remove();
        }

        function addTemplateRow() {
            const container = document.getElementById('template-container');
            const newRow = document.createElement('div');
            newRow.className = 'template-item';
            newRow.style.display = 'grid';
            newRow.style.gridTemplateColumns = '1fr 1fr auto';
            newRow.style.gap = '12px';
            newRow.style.alignItems = 'center';
            newRow.style.marginBottom = '12px';

            const index = container.children.length;
            newRow.innerHTML = `
                <input type="text" name="specifications_template[${index}][name]" placeholder="e.g. Power" value="" class="form-control">
                <input type="text" name="specifications_template[${index}][unit]" placeholder="e.g. HP" value="" class="form-control">
                <button type="button" class="btn-remove-template" onclick="removeTemplateRow(this)">Remove</button>
            `;
            container.appendChild(newRow);
        }

        function removeTemplateRow(button) {
            button.parentElement.remove();
        }
    </script>
@endsection