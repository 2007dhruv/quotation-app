@extends('layouts.app')

@section('title', 'Create Product Master - Quotation App')

@section('styles')
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Navigation breadcrumb - UNIFIED */
        .nav-breadcrumb {
            background: #f3f4f6;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            border-left: 4px solid #667eea;
        }

        .nav-breadcrumb a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-breadcrumb a:hover {
            text-decoration: underline;
            color: #764ba2;
        }

        .nav-breadcrumb span {
            color: #6b7280;
        }

        /* UNIFIED FORM STYLES */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .form-group label span {
            color: #dc2626;
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin: 28px 0 18px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        /* UNIFIED ACCESSORIES STYLING */
        .accessories-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .accessory-group {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .accessory-group:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .accessory-group h4 {
            margin: 0 0 16px 0;
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .accessory-item {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }

        .accessory-item input {
            flex: 1;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .accessory-item input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-remove-accessory {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-remove-accessory:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .btn-add-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            margin-top: 12px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-add-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* UNIFIED BUTTONS */
        .btn-primary,
        .btn-secondary,
        .btn-success {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #059669;
            color: white;
        }

        .btn-success:hover {
            background: #047857;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .accessories-grid {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 0 16px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="nav-breadcrumb">
        <span>📦 Product Masters</span>
        <span>/</span>
        <a href="{{ route('master.index') }}">Masters List</a>
        <span>/</span>
        <span style="color: #374151; font-weight: 600;">Create Master</span>
    </div>

    <div class="form-container">
        <div class="card">
            <div class="card-header">
                <h1>Create New Product Master</h1>
                <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0 0;">Define the main product with basic details,
                    image, and accessories</p>
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

                <form action="{{ route('master.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h3 class="section-title">Basic Information</h3>

                    <div class="form-group">
                        <label for="product_name">Product Name <span>*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            value="{{ old('product_name') }}" required placeholder="e.g. Hydraulic Shearing Machine">
                        @error('product_name')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-group">
                        <label for="product_image">Product Image</label>
                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                        <small style="color: #6b7280;">Accepted formats: JPG, PNG, GIF, WEBP (Max 10MB)</small>
                        @error('product_image')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-group">
                        <label for="note">Product Note</label>
                        <textarea class="form-control" id="note" name="note" rows="4"
                            placeholder="Add any additional notes about this product...">{{ old('note') }}</textarea>
                        @error('note')<small style="color: #dc2626;">{{ $message }}</small>@enderror
                    </div>

                    <h3 class="section-title">Accessories</h3>

                    <div class="accessories-grid">
                        <!-- Standard Accessories -->
                        <div class="accessory-group">
                            <h4>✓ Standard Accessories</h4>
                            <div id="standard-accessories-container">
                                <div class="accessory-item">
                                    <input type="text" name="standard_accessories[]" placeholder="e.g. Hydraulic Pump"
                                        value="">
                                    <button type="button" class="btn-remove-accessory"
                                        onclick="removeAccessoryRow(this)">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add-row" onclick="addAccessoryRow('standard')">+ Add Standard
                                Accessory</button>
                        </div>

                        <!-- Optional Accessories -->
                        <div class="accessory-group">
                            <h4>◇ Optional Accessories</h4>
                            <div id="optional-accessories-container">
                                <div class="accessory-item">
                                    <input type="text" name="optional_accessories[]" placeholder="e.g. Digital Display"
                                        value="">
                                    <button type="button" class="btn-remove-accessory"
                                        onclick="removeAccessoryRow(this)">Remove</button>
                                </div>
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
                            <div class="template-item"
                                style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: center; margin-bottom: 12px;">
                                <input type="text" name="specifications_template[0][name]" placeholder="e.g. Cutting Length"
                                    value="" class="form-control">
                                <input type="text" name="specifications_template[0][unit]" placeholder="e.g. MM" value=""
                                    class="form-control">
                                <button type="button" class="btn-remove-template"
                                    onclick="removeTemplateRow(this)">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn-add-row" onclick="addTemplateRow()">+ Add Specification</button>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Create Product Master</button>
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