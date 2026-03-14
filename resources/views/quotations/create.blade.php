@extends('layouts.app')

@section('title', 'Create Quotation - Quotation App')

@section('styles')
    <style>
        /* Card Styling */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0;
        }

        .card-header h1,
        .card-header h2 {
            margin: 0;
            color: #374151;
        }

        .card-header h1 {
            font-size: 24px;
        }

        .card-header h2 {
            font-size: 18px;
        }

        .card-body {
            padding: 24px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .text-muted {
            color: #6b7280;
        }

        .alert {
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #86efac;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-container {
            position: relative;
        }

        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-dropdown.active {
            display: block;
        }

        .search-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }

        .search-item:hover {
            background: #f3f4f6;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item .name {
            font-weight: 500;
            color: #374151;
        }

        .search-item .info {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .customer-search-row {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .customer-search-row>div {
            flex: 1;
        }

        .selected-customer {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 16px;
            margin-top: 12px;
        }

        .selected-customer h4 {
            color: #166534;
            margin-bottom: 8px;
        }

        .selected-customer p {
            font-size: 13px;
            color: #374151;
            margin: 2px 0;
        }

        .selected-customer .remove-btn {
            float: right;
            background: #dc2626;
            color: #fff;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            color: #374151;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table th {
            background: #f9fafb;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
        }

        .items-table td {
            vertical-align: middle;
        }

        .items-table input {
            width: 100%;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .items-table input[type="number"] {
            width: 80px;
        }

        .totals-section {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-box {
            width: 350px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .totals-row.total {
            border-top: 2px solid #e5e7eb;
            margin-top: 8px;
            padding-top: 16px;
            font-weight: 600;
            font-size: 16px;
        }

        .totals-row input {
            width: 120px;
            text-align: right;
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .add-product-section {
            margin-top: 20px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .add-product-section h4 {
            margin-bottom: 16px;
            color: #374151;
        }

        .add-product-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
        }

        .product-specs {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        .terms-checkbox-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
        }

        .term-checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
        }

        .term-checkbox-item input[type="checkbox"] {
            margin-top: 4px;
            cursor: pointer;
        }

        .term-checkbox-item label {
            cursor: pointer;
            flex: 1;
        }

        .term-checkbox-item .title {
            font-weight: 500;
            color: #374151;
        }

        .term-checkbox-item .description {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .term-checkbox-item input[type="checkbox"]:checked+label {
            color: #065f46;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .add-product-grid {
                grid-template-columns: 1fr;
            }

            .customer-search-row {
                flex-direction: column;
                align-items: stretch;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .totals-section {
                justify-content: stretch;
            }

            .totals-box {
                width: 100%;
            }

            .customer-search-row {
                flex-direction: column;
                align-items: stretch;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        /* Quill Editor Styling */
        .ql-toolbar {
            border: 1px solid #d1d5db;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            background: #f9fafb;
        }

        .ql-container {
            border: 1px solid #d1d5db;
            border-radius: 0 0 6px 6px;
            font-size: 14px;
        }

        .ql-editor {
            min-height: 300px;
            padding: 15px;
        }

        .ql-editor.ql-blank::before {
            color: #9ca3af;
        }

        /* Letter body section spacing */
        #quotation_letter_body {
            margin-top: 8px;
        }
    </style>

    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

@endsection

@section('content')
<form id="quotationForm" action="{{ route('quotations.store') }}" method="POST">
    @csrf

    <!-- Header -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h1>Create New Quotation</h1>
            <a href="{{ route('quotations.index') }}" class="btn btn-secondary">← Back to List</a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Company *</label>
                    <select name="company_id" id="company_id" class="form-control" required
                        onchange="loadCompanyDefaultLetterBody()">
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-short-name="{{ $company->company_short_name }}"
                                data-default-letter="{{ base64_encode($company->default_letter_body ?? '') }}">
                                {{ $company->company_name }} ({{ $company->company_short_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quotation Date *</label>
                    <input type="date" name="quotation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Valid Until</label>
                    <input type="date" name="valid_until" class="form-control"
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                </div>
                <div class="form-group">
                    <label>Subject (Optional)</label>
                    <input type="text" name="subject" class="form-control"
                        placeholder="Leave blank for auto-generated subject">
                </div>
            </div>

            <!-- Letter Body for PDF -->
            <div class="form-group" style="margin-top: 20px; margin-bottom: 24px; ">
                <label>Letter Body (Full Customizable Content)</label>
                <div id="quotation_letter_body" style="min-height:150px; max-height:300px; overflow-y:auto;"></div>
                <small class="text-muted"
                    style="display: block; margin-top: 8px; color: #6b7280; font-size: 12px;">Leave blank to use default
                    letter format. This text will replace the entire introduction and closing section in the
                    PDF.</small>
                <input type="hidden" id="quotation_letter_body_hidden" name="quotation_letter_body" value="">
            </div>
        </div>
    </div>

    <!-- Customer Selection -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2 style="font-size: 16px; color: #333; margin: 0;">Customer Information</h2>
        </div>
        <div class="card-body">
            <div class="customer-search-row">
                <div class="form-group">
                    <label>Search Customer by Name *</label>
                    <div class="search-container">
                        <input type="text" id="customerSearch" class="form-control"
                            placeholder="Type customer name to search...">
                        <div id="customerDropdown" class="search-dropdown"></div>
                    </div>
                    <input type="hidden" name="customer_id" id="customerId" required>
                </div>
                <button type="button" class="btn btn-primary" onclick="openAddCustomerModal()">+ Add New</button>
            </div>

            <div id="selectedCustomer" class="selected-customer hidden">
                <button type="button" class="remove-btn" onclick="removeCustomer()">✕ Remove</button>
                <h4 id="customerName"></h4>
                <p id="customerAddress"></p>
                <p id="customerContact"></p>
                <p id="customerGst"></p>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2 style="font-size: 16px; color: #333; margin: 0;">Products / Items</h2>
        </div>
        <div class="card-body">
            <!-- Add Product Section -->
            <div class="add-product-section">
                <h4>Add Product</h4>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Search Product by Name : </label>
                    <div class="search-container">
                        <input type="text" id="productSearch" class="form-control" placeholder="Type product name...">
                        <div id="productDropdown" class="search-dropdown"></div>
                    </div>
                </div>
                <div id="addProductForm" class="hidden">
                    <div class="add-product-grid">
                        <div>
                            <label style="font-size: 12px; color: #6b7280;">Product Name</label>
                            <div id="selectedProductName" style="font-weight: 500; color: #374151;"></div>
                            <div id="selectedProductSpecs" class="product-specs"></div>
                            <input type="hidden" id="selectedProductId">
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280;">Product Model</label>
                            <div id="selectedProductModel" style="font-weight: 500; color: #374151; padding: 8px 0;">
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280;">Quantity</label>
                            <input type="number" id="productQty" class="form-control" value="1" min="1">
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280;">Unit Price (₹)</label>
                            <input type="number" id="productPrice" class="form-control" step="0.01" min="0">
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280;">Total (₹)</label>
                            <div id="productTotal" style="font-weight: 600; color: #374151; padding: 10px 0;">0.00</div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success" onclick="addProductToList()">+ Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div id="itemsContainer">
                <table class="items-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Product Model</th>
                            <th width="10%">Qty</th>
                            <th width="15%">Unit Price</th>
                            <th width="15%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr id="noItemsRow">
                            <td colspan="7" style="text-align: center; color: #9ca3af; padding: 40px;">
                                No products added yet. Search and add products above.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <div class="totals-box">
                    <div class="totals-row">
                        <span>Subtotal:</span>
                        <span id="subtotalDisplay">₹0.00</span>
                    </div>
                    <div class="totals-row">
                        <span>Discount (%):</span>
                        <input type="number" name="discount_percent" id="discountPercent" value="0" min="0" max="100"
                            step="0.01" onchange="calculateTotals()">
                    </div>
                    <div class="totals-row">
                        <span>Discount Amount:</span>
                        <span id="discountAmountDisplay">₹0.00</span>
                    </div>
                    <div class="totals-row">
                        <span>Tax (%):</span>
                        <input type="number" name="tax_percent" id="taxPercent" value="0" min="0" max="100" step="0.01"
                            onchange="calculateTotals()">
                    </div>
                    <div class="totals-row">
                        <span>Tax Amount:</span>
                        <span id="taxAmountDisplay">₹0.00</span>
                    </div>
                    <div class="totals-row total">
                        <span>Grand Total:</span>
                        <span id="grandTotalDisplay">₹0.00</span>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for financial calculations -->
            <input type="hidden" name="subtotal" id="hiddenSubtotal" value="0">
            <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">
            <input type="hidden" name="tax_amount" id="hiddenTaxAmount" value="0">
            <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="0">
        </div>
    </div>

    <!-- Notes & Terms & Conditions -->
    <div class="card">
        <div class="card-body">
            <h3 style="margin-bottom: 16px; color: #374151; font-size: 16px; margin-top: 0;">Terms & Conditions</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 16px;">Select which terms & conditions to include
                in the quotation PDF:</p>

            @if($termsConditions->count() > 0)
                <div class="terms-checkbox-list" style="margin-bottom: 24px;">
                    @foreach($termsConditions as $term)
                        <div class="term-checkbox-item">
                            <input type="checkbox" id="term_{{ $term->id }}" name="terms_conditions[]" value="{{ $term->id }}"
                                checked>
                            <label for="term_{{ $term->id }}">
                                <div class="title">{{ $term->title }}</div>
                                <div class="description">
                                    {{ substr($term->description, 0, 100) }}{{ strlen($term->description) > 100 ? '...' : '' }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    style="padding: 20px; background: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b; color: #92400e; margin-bottom: 24px;">
                    No active terms & conditions available. <a href="{{ route('terms-conditions.index') }}"
                        style="color: #b45309;">Create one</a>
                </div>
            @endif

            <div class="form-group">
                <label>Additional Notes</label>
                <textarea name="notes" class="form-control" rows="4"
                    placeholder="Add any additional notes..."></textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">Create Quotation</button>
            </div>
        </div>
    </div>
</form>

<!-- Add Customer Modal - OUTSIDE the main form -->
<div id="addCustomerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Customer</h2>
            <button type="button" class="modal-close" onclick="closeAddCustomerModal()">×</button>
        </div>
        <form id="addCustomerForm" onsubmit="handleAddCustomer(event)">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="customerNameInput">Customer Name *</label>
                    <input type="text" id="customerNameInput" name="customer_name" class="form-control"
                        placeholder="Enter customer name" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="addressInput">Address</label>
                    <textarea id="addressInput" name="address" class="form-control" placeholder="Enter full address"
                        style="min-height: 80px; resize: vertical;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label for="cityInput">City</label>
                        <input type="text" id="cityInput" name="city" class="form-control" placeholder="Enter city">
                    </div>
                    <div class="form-group">
                        <label for="stateInput">State</label>
                        <input type="text" id="stateInput" name="state" class="form-control" placeholder="Enter state">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="pinCodeInput">Pin Code</label>
                    <input type="text" id="pinCodeInput" name="pin_code" class="form-control"
                        placeholder="Enter pin code (6 digits)" pattern="[0-9]{6}" title="Pin code must be 6 digits">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="mobileInput">Mobile Number *</label>
                    <input type="tel" id="mobileInput" name="mobile" class="form-control"
                        placeholder="Enter 10 digit mobile number" required pattern="[0-9]{10}"
                        title="Mobile number must be exactly 10 digits">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="emailInput">Email</label>
                    <input type="email" id="emailInput" name="email" class="form-control"
                        placeholder="Enter email address">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="gstNoInput">GST Number</label>
                    <input type="text" id="gstNoInput" name="gst_no" class="form-control"
                        placeholder="e.g., 27AABCU9603R1Z5">
                </div>

                <div class="form-group">
                    <label for="gstTypeSelect">GST Type *</label>
                    <select id="gstTypeSelect" name="gst_type" class="form-control" required>
                        <option value="">-- Select GST Type --</option>
                        <option value="instate">In-State (CGST + SGST 9% + 9%)</option>
                        <option value="outofstate">Out-of-State (IGST 18%)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddCustomerModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save & Select Customer</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
    <script>
        // Store products data
        const productsData = @json($products);
        let itemCounter = 0;
        let items = [];

        // Customer Search
        const customerSearch = document.getElementById('customerSearch');
        const customerDropdown = document.getElementById('customerDropdown');
        let customerSearchTimeout;

        customerSearch.addEventListener('input', function () {
            clearTimeout(customerSearchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                customerDropdown.classList.remove('active');
                return;
            }

            customerSearchTimeout = setTimeout(() => {
                fetch(`{{ url('/api/customers/search') }}?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(customers => {
                        if (customers.length === 0) {
                            customerDropdown.innerHTML = '<div class="search-item"><div class="info">No customers found</div></div>';
                        } else {
                            customerDropdown.innerHTML = customers.map(c => `
                                                        <div class="search-item" onclick="selectCustomer(${JSON.stringify(c).replace(/"/g, '&quot;')})">
                                                            <div class="name">${c.customer_name}</div>
                                                            <div class="info">${c.city || ''} | ${c.mobile || ''}</div>
                                                        </div>
                                                    `).join('');
                        }
                        customerDropdown.classList.add('active');
                    });
            }, 300);
        });

        function selectCustomer(customer) {
            document.getElementById('customerId').value = customer.id;
            document.getElementById('customerName').textContent = customer.customer_name;
            document.getElementById('customerAddress').textContent = customer.address ? `Address: ${customer.address}` : '';
            document.getElementById('customerContact').textContent = `Mobile: ${customer.mobile || 'N/A'}`;
            document.getElementById('customerGst').textContent = customer.gst_no ? `GST: ${customer.gst_no}` : '';
            document.getElementById('selectedCustomer').classList.remove('hidden');
            customerDropdown.classList.remove('active');
            customerSearch.value = '';

            // Auto-fill Tax based on customer GST type
            const taxInput = document.getElementById('taxPercent');
            if (customer.gst_type === 'instate' || customer.gst_type === 'outofstate') {
                taxInput.value = '18';
            } else {
                taxInput.value = '0';
            }
            calculateTotals();
        }

        function removeCustomer() {
            document.getElementById('customerId').value = '';
            document.getElementById('selectedCustomer').classList.add('hidden');
        }

        // Product Search
        const productSearch = document.getElementById('productSearch');
        const productDropdown = document.getElementById('productDropdown');
        let productSearchTimeout;

        productSearch.addEventListener('input', function () {
            clearTimeout(productSearchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                productDropdown.classList.remove('active');
                return;
            }

            productSearchTimeout = setTimeout(() => {
                fetch(`{{ url('/api/products/search') }}?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(products => {
                        if (products.length === 0) {
                            productDropdown.innerHTML = '<div class="search-item"><div class="info">No products found</div></div>';
                        } else {
                            productDropdown.innerHTML = products.map((p, idx) => {
                                const specs = p.specifications ? p.specifications.map(s => `${s.spec_name}: ${s.spec_value}${s.spec_unit || ''}`).join(' | ') : '';
                                const jsonEncoded = btoa(JSON.stringify(p)); // Encode to base64 to avoid quote issues
                                return `
                                                            <div class="search-item" data-product="${jsonEncoded}" onclick="selectProductFromDropdown(this)">
                                                                <div class="name">${p.product_name}</div>
                                                                <div class="info">${p.product_type || 'N/A'} | ₹${parseFloat(p.default_price).toFixed(2)}</div>
                                                                ${specs ? `<div class="info">${specs}</div>` : ''}
                                                            </div>
                                                        `;
                            }).join('');
                        }
                        productDropdown.classList.add('active');
                    });
            }, 300);
        });

        function selectProductFromDropdown(element) {
            const jsonEncoded = element.getAttribute('data-product');
            const product = JSON.parse(atob(jsonEncoded));
            selectProduct(product);
        }

        function selectProduct(product) {
            document.getElementById('selectedProductId').value = product.id;
            document.getElementById('selectedProductName').textContent = product.product_name;
            document.getElementById('selectedProductModel').textContent = product.product_model || 'N/A';
            document.getElementById('productPrice').value = parseFloat(product.default_price).toFixed(2);

            const specs = product.specifications ? product.specifications.map(s => `${s.spec_name}: ${s.spec_value}${s.spec_unit || ''}`).join(' | ') : '';
            document.getElementById('selectedProductSpecs').textContent = specs;

            document.getElementById('addProductForm').classList.remove('hidden');
            productDropdown.classList.remove('active');
            productSearch.value = '';

            updateProductTotal();
        }

        document.getElementById('productQty').addEventListener('input', updateProductTotal);
        document.getElementById('productPrice').addEventListener('input', updateProductTotal);

        function updateProductTotal() {
            const qty = parseFloat(document.getElementById('productQty').value) || 0;
            const price = parseFloat(document.getElementById('productPrice').value) || 0;
            const total = qty * price;
            document.getElementById('productTotal').textContent = total.toFixed(2);
        }

        function addProductToList() {
            const productId = document.getElementById('selectedProductId').value;
            const productName = document.getElementById('selectedProductName').textContent;
            const productModel = document.getElementById('selectedProductModel').textContent;
            const qty = parseInt(document.getElementById('productQty').value) || 1;
            const price = parseFloat(document.getElementById('productPrice').value) || 0;
            const total = qty * price;

            if (!productId) {
                alert('Please select a product first');
                return;
            }

            if (price <= 0) {
                alert('Please enter a valid price');
                return;
            }

            itemCounter++;
            const item = {
                id: itemCounter,
                product_id: productId,
                product_name: productName,
                product_model: productModel,
                quantity: qty,
                unit_price: price,
                total: total
            };

            items.push(item);
            renderItems();

            // Reset form
            document.getElementById('addProductForm').classList.add('hidden');
            document.getElementById('selectedProductId').value = '';
            document.getElementById('selectedProductName').textContent = '';
            document.getElementById('selectedProductModel').textContent = '';
            document.getElementById('selectedProductSpecs').textContent = '';
            document.getElementById('productQty').value = 1;
            document.getElementById('productPrice').value = '';
            document.getElementById('productTotal').textContent = '0.00';
        }

        function renderItems() {
            const tbody = document.getElementById('itemsBody');

            if (items.length === 0) {
                tbody.innerHTML = `
                                            <tr id="noItemsRow">
                                                <td colspan="7" style="text-align: center; color: #9ca3af; padding: 40px;">
                                                    No products added yet. Search and add products above.
                                                </td>
                                            </tr>
                                        `;
            } else {
                tbody.innerHTML = items.map((item, index) => `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>
                                                    <strong>${item.product_name}</strong>
                                                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                                                    <input type="hidden" name="items[${index}][product_type]" value="${item.product_model}">
                                                    <input type="hidden" name="items[${index}][description]" value="">
                                                </td>
                                                <td>
                                                    <input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" 
                                                        onchange="updateItemQuantity(${item.id}, this.value)" style="width: 70px;">
                                                </td>
                                                <td>
                                                    <input type="number" name="items[${index}][unit_price]" value="${item.unit_price.toFixed(2)}" min="0" step="0.01"
                                                        onchange="updateItemPrice(${item.id}, this.value)" style="width: 100px;">
                                                </td>
                                                <td style="font-weight: 600;">₹${item.total.toFixed(2)}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${item.id})">✕</button>
                                                </td>
                                            </tr>
                                        `).join('');
            }

            calculateTotals();
        }

        function updateItemQuantity(itemId, qty) {
            const item = items.find(i => i.id === itemId);
            if (item) {
                item.quantity = parseInt(qty) || 1;
                item.total = item.quantity * item.unit_price;
                renderItems();
            }
        }

        function updateItemPrice(itemId, price) {
            const item = items.find(i => i.id === itemId);
            if (item) {
                item.unit_price = parseFloat(price) || 0;
                item.total = item.quantity * item.unit_price;
                renderItems();
            }
        }

        function removeItem(itemId) {
            items = items.filter(i => i.id !== itemId);
            renderItems();
        }

        function calculateTotals() {
            const subtotal = items.reduce((sum, item) => sum + item.total, 0);
            const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
            const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
            const discountAmount = (subtotal * discountPercent) / 100;
            const afterDiscount = subtotal - discountAmount;
            const taxAmount = (afterDiscount * taxPercent) / 100;
            const grandTotal = afterDiscount + taxAmount;

            document.getElementById('subtotalDisplay').textContent = `₹${subtotal.toFixed(2)}`;
            document.getElementById('discountAmountDisplay').textContent = `₹${discountAmount.toFixed(2)}`;
            document.getElementById('taxAmountDisplay').textContent = `₹${taxAmount.toFixed(2)}`;
            document.getElementById('grandTotalDisplay').textContent = `₹${grandTotal.toFixed(2)}`;

            // Update hidden inputs
            document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
            document.getElementById('hiddenDiscountAmount').value = discountAmount.toFixed(2);
            document.getElementById('hiddenTaxAmount').value = taxAmount.toFixed(2);
            document.getElementById('hiddenTotalAmount').value = grandTotal.toFixed(2);
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.search-container')) {
                customerDropdown.classList.remove('active');
                productDropdown.classList.remove('active');
            }
        });

        // Form validation - moved inside DOMContentLoaded for proper Quill sync
        var validationListenerAdded = false;

        // Initialize form when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupForm);
        } else {
            setupForm();
        }

        // Load company's default letter body
        function loadCompanyDefaultLetterBody() {
            const companySelect = document.getElementById('company_id');

            // Wait for Quill to be ready
            setTimeout(function () {
                const selectedOption = companySelect.options[companySelect.selectedIndex];
                const defaultLetterB64 = selectedOption.getAttribute('data-default-letter');

                if (defaultLetterB64 && defaultLetterB64.trim() !== '') {
                    try {
                        // Decode base64 to HTML
                        const defaultLetter = atob(defaultLetterB64);
                        const hiddenInput = document.getElementById('quotation_letter_body_hidden');

                        if (hiddenInput) {
                            hiddenInput.value = defaultLetter;
                        }

                        // If quill is already initialized, update it
                        if (typeof quill !== 'undefined' && quill) {
                            quill.root.innerHTML = defaultLetter;
                            console.log('✅ Loaded company default letter body');
                        } else {
                            console.warn('⚠️  Quill not ready yet for auto-load');
                        }
                    } catch (error) {
                        console.error('Error loading default letter:', error);
                    }
                }
            }, 100);
        }

        function setupForm() {
            // Initialize Quill editor for Letter Body
            window.quill = new Quill('#quotation_letter_body', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'header': 1 }, { 'header': 2 }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'script': 'sub' }, { 'script': 'super' }],
                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [false, 1, 2, 3, 4, 5, 6] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'font': [] }],
                        [{ 'align': [] }],
                        ['clean'],
                        ['link', 'image']
                    ]
                },
                placeholder: 'Enter your letter content here...',
                formats: ['bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block', 'header', 'indent', 'list', 'script', 'align', 'size', 'header', 'color', 'background', 'font', 'link', 'image']
            });

            console.log('✓ Quill editor initialized for CREATE form');

            // Auto-load company's default letter body if a company is already selected
            setTimeout(function () {
                const companySelect = document.getElementById('company_id');
                if (companySelect && companySelect.value) {
                    loadCompanyDefaultLetterBody();
                }
            }, 50);

            // CONTINUOUS SYNC: Use text-change event to keep hidden input updated in real-time
            const hiddenInput = document.getElementById('quotation_letter_body_hidden');
            window.quill.on('text-change', function (delta, oldDelta, source) {
                const quillHTML = window.quill.root.innerHTML;
                hiddenInput.value = quillHTML;
                console.log('📝 CREATE form: Auto-synced on text change, length:', quillHTML.length);
            });

            // Attach form submission handlers
            const quotationForm = document.getElementById('quotationForm');
            if (quotationForm && !validationListenerAdded) {
                validationListenerAdded = true;

                quotationForm.addEventListener('submit', function (e) {
                    if (!document.getElementById('customerId').value) {
                        e.preventDefault();
                        alert('Please select a customer');
                        return;
                    }

                    if (!document.getElementById('company_id').value) {
                        e.preventDefault();
                        alert('Please select a company');
                        return;
                    }

                    if (items.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one product');
                        return;
                    }

                    // Recalculate totals before submission
                    calculateTotals();

                    // SYNC QUILL CONTENT (backup sync in case text-change didn't catch it)
                    const quillContent = window.quill.root.innerHTML;
                    hiddenInput.value = quillContent;

                    console.log('🔴 CREATE form SUBMISSION');
                    console.log('  Letter body length:', quillContent.length);
                    console.log('  Preview:', quillContent.substring(0, 80));
                    console.log('  Hidden input confirmed:', hiddenInput.value.substring(0, 80));
                });
            }
        }


        // Add Customer Modal Functions
        function openAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.add('active');
        }

        function closeAddCustomerModal() {
            const modal = document.getElementById('addCustomerModal');
            modal.classList.remove('active');

            // Reset form
            const form = document.getElementById('addCustomerForm');
            if (form) {
                form.reset();
                // Re-enable submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save & Select Customer';
                }
            }
        }

        function handleAddCustomer(event) {
            event.preventDefault();

            const form = document.getElementById('addCustomerForm');
            const formData = new FormData(form);

            // Disable submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            fetch('{{ route("customers.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => {
                            if (!response.ok) {
                                throw { status: response.status, data: data };
                            }
                            return data;
                        });
                    } else {
                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                        }
                        return null;
                    }
                })
                .then(data => {
                    if (!data) {
                        // Redirect fallback
                        window.location.reload();
                        return;
                    }

                    console.log('Customer created:', data);

                    // Prepare customer object for selection
                    const customer = {
                        id: data.id,
                        customer_name: data.customer_name,
                        address: data.address || '',
                        city: data.city || '',
                        state: data.state || '',
                        mobile: data.mobile || '',
                        gst_no: data.gst_no || '',
                        gst_type: data.gst_type || ''
                    };

                    selectNewCustomer(customer);
                })
                .catch(error => {
                    console.error('Error:', error);

                    let errorMsg = 'Unknown error occurred';
                    if (error.data && error.data.errors) {
                        errorMsg = Object.values(error.data.errors).flat().join('\n');
                    } else if (error.data && error.data.message) {
                        errorMsg = error.data.message;
                    } else if (error.message) {
                        errorMsg = error.message;
                    }

                    alert('Error:\n' + errorMsg);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save & Select Customer';
                });
        }

        function selectNewCustomer(customer) {
            selectCustomer(customer);
            closeAddCustomerModal();

            // Show success notification
            const notification = document.createElement('div');
            notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 16px 24px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 99999; font-weight: 500;';
            notification.innerHTML = '✓ Customer added and selected successfully!';
            document.body.appendChild(notification);

            setTimeout(() => notification.remove(), 4000);
        }

        // Close modal when clicking outside
        window.addEventListener('click', function (event) {
            const modal = document.getElementById('addCustomerModal');
            if (event.target == modal) {
                closeAddCustomerModal();
            }
        });
    </script>
@endsection