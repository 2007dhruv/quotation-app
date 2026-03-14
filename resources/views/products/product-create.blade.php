@extends('layouts.app')

@section('title', 'Add Product Model - Quotation App')

@section('styles')
    <style>
        .form-container {
            max-width: 900px;
        }

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

        .nav-breadcrumb a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-breadcrumb a:hover {
            text-decoration: underline;
        }

        .nav-breadcrumb span {
            color: #6b7280;
        }

        /* Master selector card */
        .master-selector {
            background: #ffffffff;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: white;
            border: 2px solid #9ca3af;
        }

        .master-selector h2 {
            margin: 0 0 16px 0;
            font-size: 18px;
        }

        .master-selector .form-group {
            margin-bottom: 12px;
        }

        .master-selector label {
            color: rgba(255, 255, 255, 0.9);
        }

        .master-selector select {
            background: white;
            color: #374151;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Master info display */
        .master-info-card {
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            display: none;
        }

        .master-info-card.active {
            display: block;
        }

        .master-info-card h3 {
            margin: 0 0 12px 0;
            color: #15803d;
            font-size: 16px;
        }

        .master-info-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .info-item {
            font-size: 13px;
        }

        .info-item strong {
            display: block;
            color: #374151;
            margin-bottom: 4px;
        }

        .info-item span {
            color: #6b7280;
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
        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #2b2b2bff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
        }

        .spec-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 12px;
            margin-bottom: 12px;
            align-items: end;
        }

        .spec-row .form-group {
            margin-bottom: 0;
        }

        .spec-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 12px;
            margin-bottom: 8px;
        }

        .spec-header span {
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
        }

        #specs-container {
            margin-bottom: 16px;
        }

        .btn-remove {
            padding: 8px 12px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-remove:hover {
            background: #dc2626;
        }

        .btn-add {
            padding: 8px 16px;
            background: #059669;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-add:hover {
            background: #047857;
        }

        .disabled-section {
            opacity: 0.5;
            pointer-events: none;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .spec-row {
                grid-template-columns: 1fr;
            }

            .master-info-content {
                grid-template-columns: 1fr;
            }

            .spec-header {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <div class="nav-breadcrumb">
        <span>🔧 Product Models</span>
        <span>/</span>
        <a href="{{ route('products.index') }}">Back to Models</a>
        <span>•</span>
        <span style="color: #374151; font-weight: 500;">Add New</span>
        <span>|</span>
        <a href="{{ route('master.index') }}">View Main Products</a>
    </div>

    <div class="form-container">
        <div class="card">
            <div class="card-header">
                @if($preselectedMaster)
                    <h1>Add "{{ $preselectedMaster->product_name }}" Models</h1>
                    <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0 0;">Add new models with specifications and
                        pricing for <strong>{{ $preselectedMaster->product_name }}</strong>.</p>
                @else
                    <h1>Add Product Model</h1>
                    <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0 0;">Select a main product, then add a new model
                        with specifications and pricing.</p>
                @endif
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

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <!-- HIDDEN MAIN PRODUCT SELECTION -->
                    @if($preselectedMasterId)
                        <!-- Auto-select the master product and hide the selector UI completely -->
                        <input type="hidden" id="product_master_id" name="product_master_id" value="{{ $preselectedMasterId }}">
                    @else
                        <!-- Only show if NO master ID was passed (fallback) -->
                        <div class="master-selector">
                            <h2>📦 Step 1: Select Main Product</h2>
                            <div class="form-group">
                                <label for="product_master_id" style="color: rgba(0, 0, 0, 0.9);">Main Product
                                    <span>*</span></label>
                                <select class="form-select" style="border: 2px solid #9ca3af;" id="product_master_id"
                                    name="product_master_id" required onchange="loadMasterDetails()">
                                    <option value="">-- Choose a Main Product --</option>
                                    @foreach($productMasters as $master)
                                        <option value="{{ $master->id }}">
                                            {{ $master->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_master_id')<small
                                style="color: rgba(255,255,255,0.9);">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <!-- Master Info Display (Only useful if selecting manually) -->
                        <div id="masterInfo" class="master-info-card">
                            <h3>✓ Main Product Info</h3>
                            <div class="master-info-content">
                                <div class="info-item">
                                    <strong>Note:</strong>
                                    <span id="masterNote">-</span>
                                </div>
                                <div class="info-item">
                                    <strong>Standard Accessories:</strong>
                                    <span id="masterStd">-</span>
                                </div>
                                <div class="info-item">
                                    <strong>Optional Accessories:</strong>
                                    <span id="masterOpt">-</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- STEP 2: ADD MODEL DETAILS -->
                    <div id="step2-content" class="{{ $preselectedMasterId ? '' : 'disabled-section' }}">
                        <!-- MULTIPLE PRODUCTS MODE (Default) -->
                        <div id="multiple-mode">
                            <h4 style="color: #374151; margin-bottom: 16px; font-size: 15px; font-weight: 600;">📦 Add
                                Multiple Product Models</h4>

                            <!-- Specifications Table (Spreadsheet Style) -->
                            <div
                                style="overflow-x: auto; margin-bottom: 12px; border: 2px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <table
                                    style="width: 100%; border-collapse: collapse; background: white; table-layout: fixed;">
                                    <colgroup>
                                        <col style="width: 130px;">
                                        <!-- Spec columns will be added here dynamically (120px each) -->
                                        <col style="width: 110px;">
                                        <col style="width: 75px;">
                                    </colgroup>
                                    <thead>
                                        <tr id="header-row"
                                            style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; font-weight: 600;">
                                            <th
                                                style="padding: 14px 12px; text-align: left; border: 1px solid #cbd5e1; font-size: 13px; overflow: hidden; text-overflow: ellipsis;">
                                                Model Name</th>
                                            <!-- Spec columns will be added here dynamically -->
                                            <th
                                                style="padding: 14px 12px; text-align: left; border: 1px solid #cbd5e1; font-size: 13px; overflow: hidden; text-overflow: ellipsis;">
                                                Price (₹)</th>
                                            <th
                                                style="padding: 14px 12px; text-align: center; border: 1px solid #cbd5e1; font-size: 13px; overflow: hidden;">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                        <!-- Product rows will be added here -->
                                    </tbody>
                                </table>
                            </div>

                            <div style="display: flex; gap: 12px; margin-top: 12px;">
                                <button type="button" class="btn-add" onclick="addProductRowForTable()"
                                    style="font-size: 14px; padding: 8px 16px;">+ Add Another Model</button>
                                <button type="button" class="btn-primary" onclick="addCustomSpecificationColumn()"
                                    style="font-size: 14px; padding: 8px 16px; background: #8b5cf6;">+ Add Custom
                                    Specification</button>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Create Product
                            Model(s)</button>
                        @if($preselectedMasterId)
                            <a href="{{ route('master.show', $preselectedMasterId) }}" class="btn btn-secondary">Cancel</a>
                        @else
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const masterData = {
            @foreach($productMasters as $master)
                                    "{{ $master->id }}": {
                    "note": @json($master->note ?? '-'),
                    "standard_accessories": @json(implode(', ', $master->getStandardAccessoriesArray()) ?: 'None'),
                    "optional_accessories": @json(implode(', ', $master->getOptionalAccessoriesArray()) ?: 'None')
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
                };

        function loadMasterDetails() {
            const masterId = document.getElementById('product_master_id').value;
            const masterInfo = document.getElementById('masterInfo');
            const step2Title = document.getElementById('step2-title');
            const step2Content = document.getElementById('step2-content');
            const submitBtn = document.getElementById('submitBtn');
            const loadTemplateBtn = document.getElementById('loadTemplateBtn');

            console.log('Master ID selected:', masterId); // Debug log

            // If masterInfo exists, we are showing the full UI form
            if (masterInfo) {
                if (masterId && masterData[masterId]) {
                    const data = masterData[masterId];
                    document.getElementById('masterNote').textContent = data.note;
                    document.getElementById('masterStd').textContent = data.standard_accessories;
                    document.getElementById('masterOpt').textContent = data.optional_accessories;

                    masterInfo.classList.add('active');
                    if (step2Title) step2Title.style.display = 'block';
                    if (step2Content) {
                        step2Content.classList.remove('disabled-section');
                        step2Content.style.opacity = '1';
                        step2Content.style.pointerEvents = 'auto';
                    }
                    if (submitBtn) submitBtn.disabled = false;
                    if (loadTemplateBtn) loadTemplateBtn.style.display = 'block';

                    console.log('Form unlocked'); // Debug log
                } else {
                    masterInfo.classList.remove('active');
                    if (step2Title) step2Title.style.display = 'none';
                    if (step2Content) {
                        step2Content.classList.add('disabled-section');
                        step2Content.style.opacity = '0.5';
                        step2Content.style.pointerEvents = 'none';
                    }
                    if (submitBtn) submitBtn.disabled = true;
                    if (loadTemplateBtn) loadTemplateBtn.style.display = 'none';

                    console.log('Form locked'); // Debug log
                }
            } else if (masterId) {
                // If there is no masterInfo, we are in hidden mode. Just enable submit.
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        // Add Custom Specification Column dynamically
        function addCustomSpecificationColumn() {
            const specName = prompt("Enter the new Specification Name (e.g., Voltage):");
            if (!specName || specName.trim() === '') return;

            const specUnit = prompt("Enter the Unit for " + specName + " (e.g., V) [Optional]:") || '';

            // Add to template specs
            currentTemplateSpecs.push({
                name: specName.trim(),
                unit: specUnit.trim()
            });

            // Rebuild header
            rebuildTableHeader();

            // Update all existing rows by appending the new column cell
            const tbody = document.getElementById('products-table-body');
            const specIndex = currentTemplateSpecs.length - 1;

            Array.from(tbody.children).forEach((row, rowIndex) => {
                // Because the Price and Action columns are at the end, we need to insert the new cell *before* the Price column.
                // Model Name (1) + existing specs... Price is at length - 2. Action is at length - 1.
                const newCell = row.insertCell(row.cells.length - 2);
                newCell.style.padding = '12px';
                newCell.style.border = '1px solid #cbd5e1';
                newCell.style.overflow = 'hidden';

                newCell.innerHTML = `
                            <input type="text" name="products[${rowIndex}][specs][${specIndex}][value]" 
                                   placeholder="Value" class="form-control" 
                                   style="font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; width: 100%; box-sizing: border-box;">
                            <input type="hidden" name="products[${rowIndex}][specs][${specIndex}][name]" value="${specName.trim()}">
                            <input type="hidden" name="products[${rowIndex}][specs][${specIndex}][unit]" value="${specUnit.trim()}">
                        `;
            });
        }

        // Store template specs globally
        let currentTemplateSpecs = [];

        // Add product row for table (with spec input fields)
        function addProductRowForTable() {
            const tbody = document.getElementById('products-table-body');
            const rowIndex = tbody.children.length;

            const row = document.createElement('tr');
            row.className = 'product-row';
            row.style.backgroundColor = rowIndex % 2 === 0 ? '#f8fafc' : 'white';
            row.style.borderBottom = '1px solid #e2e8f0';

            // MODEL NAME COLUMN
            let rowHTML = `
                        <td style="padding: 12px; border: 1px solid #cbd5e1; overflow: hidden;">
                            <input type="text" name="products[${rowIndex}][product_model]" placeholder="e.g. AFBR-212" 
                                   class="form-control" style="font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; width: 100%; box-sizing: border-box;">
                        </td>
                    `;

            // SPEC COLUMNS - Must match header exactly (120px each)
            currentTemplateSpecs.forEach((spec, specIndex) => {
                rowHTML += `
                            <td style="padding: 12px; border: 1px solid #cbd5e1; overflow: hidden;">
                                <input type="text" name="products[${rowIndex}][specs][${specIndex}][value]" 
                                       placeholder="Value" class="form-control" 
                                       style="font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; width: 100%; box-sizing: border-box;">
                                <input type="hidden" name="products[${rowIndex}][specs][${specIndex}][name]" value="${spec.name}">
                                <input type="hidden" name="products[${rowIndex}][specs][${specIndex}][unit]" value="${spec.unit}">
                            </td>
                        `;
            });

            // PRICE COLUMN
            rowHTML += `
                        <td style="padding: 12px; border: 1px solid #cbd5e1; overflow: hidden;">
                            <input type="number" step="0.01" name="products[${rowIndex}][price]" 
                                   placeholder="0.00" class="form-control" 
                                   style="font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; width: 100%; box-sizing: border-box;">
                        </td>
                    `;

            // ACTION COLUMN
            rowHTML += `
                        <td style="padding: 12px; border: 1px solid #cbd5e1; text-align: center; overflow: hidden;">
                            <button type="button" class="btn-remove" onclick="removeProductRow(this)" 
                                    style="font-size: 12px; padding: 6px 12px; white-space: nowrap;">Remove</button>
                        </td>
                    `;

            row.innerHTML = rowHTML;
            tbody.appendChild(row);
        }

        // Load template and setup table columns
        async function loadTemplateForMultipleMode(showAlert = true) {
            const masterId = document.getElementById('product_master_id').value;
            if (!masterId) {
                if (showAlert) alert('Please select a master product first');
                return;
            }

            try {
                const response = await fetch(`/products/master/${masterId}/template`);
                const data = await response.json();

                console.log('Template Response:', data);  // DEBUG

                if (data.template && data.template.length > 0) {
                    // Store template specs
                    currentTemplateSpecs = data.template.map(spec => ({
                        name: typeof spec === 'string' ? spec : (spec.name || ''),
                        unit: typeof spec === 'object' ? (spec.unit || '') : ''
                    }));

                    console.log('Current Template Specs:', currentTemplateSpecs);  // DEBUG

                    // Clear existing table and rebuild header
                    rebuildTableHeader();

                    // Clear existing rows
                    const tbody = document.getElementById('products-table-body');
                    tbody.innerHTML = '';

                    // Add initial empty row
                    addProductRowForTable();

                    if (showAlert) {
                        alert(`Template loaded! ${data.count} specification${data.count !== 1 ? 's' : ''} ready.`);
                    }
                } else {
                    if (showAlert) {
                        alert('No template found for this product master. Please define specifications in the Product Master.');
                    }
                }
            } catch (error) {
                console.error('Error loading template:', error);
                if (showAlert) alert('Failed to load template. Please try again.');
            }
        }

        // Rebuild table header with spec columns
        function rebuildTableHeader() {
            const table = document.querySelector('#multiple-mode table');
            const thead = table.querySelector('thead');
            const tbody = table.querySelector('tbody');

            console.log('Rebuilding header with', currentTemplateSpecs.length, 'specs');

            // Remove old colgroup and create new one
            const oldColgroup = table.querySelector('colgroup');
            if (oldColgroup) oldColgroup.remove();

            const newColgroup = document.createElement('colgroup');

            // Add Model Name col
            let col = document.createElement('col');
            col.style.width = '130px';
            newColgroup.appendChild(col);

            // Add spec cols
            currentTemplateSpecs.forEach(spec => {
                col = document.createElement('col');
                col.className = 'spec-col';
                col.style.width = '120px';
                newColgroup.appendChild(col);
            });

            // Add Price col
            col = document.createElement('col');
            col.style.width = '110px';
            newColgroup.appendChild(col);

            // Add Action col
            col = document.createElement('col');
            col.style.width = '75px';
            newColgroup.appendChild(col);

            // Insert colgroup at start of table
            table.insertBefore(newColgroup, thead);

            // Rebuild header row
            let headerHTML = `
                        <th style="padding: 14px 12px; text-align: left; border: 1px solid #cbd5e1; font-size: 13px;">Model Name</th>
                    `;

            // Add spec headers
            currentTemplateSpecs.forEach(spec => {
                const unitText = spec.unit ? ` (${spec.unit})` : '';
                headerHTML += `<th style="padding: 14px 12px; text-align: left; border: 1px solid #cbd5e1; font-size: 13px;">${spec.name}${unitText}</th>`;
            });

            // Add Price and Action headers
            headerHTML += `
                        <th style="padding: 14px 12px; text-align: left; border: 1px solid #cbd5e1; font-size: 13px;">Price (₹)</th>
                        <th style="padding: 14px 12px; text-align: center; border: 1px solid #cbd5e1; font-size: 13px;">Action</th>
                    `;

            // Update header row
            const headerRow = thead.querySelector('tr');
            headerRow.innerHTML = headerHTML;

            // Recolor header
            Array.from(headerRow.querySelectorAll('th')).forEach(th => {
                th.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
                th.style.color = 'white';
                th.style.fontWeight = '600';
            });

            console.log('Header rebuilt successfully!');
        }

        // Load master details on page load if value exists
        window.addEventListener('load', function () {
            loadMasterDetails();

            // Auto-load template from master if master_id pre-selected
            const masterId = document.getElementById('product_master_id').value;
            if (masterId) {
                loadTemplateForMultipleMode(false); // Auto-load specs for multiple mode
            }
        });
    </script>
@endsection