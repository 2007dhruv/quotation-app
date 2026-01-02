@extends('layouts.app')

@section('title', 'Create Quotation - Quotation App')

@section('styles')
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px; color: #374151; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    
    .search-container { position: relative; }
    .search-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; max-height: 250px; overflow-y: auto; z-index: 1000; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .search-dropdown.active { display: block; }
    .search-item { padding: 12px; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
    .search-item:hover { background: #f3f4f6; }
    .search-item:last-child { border-bottom: none; }
    .search-item .name { font-weight: 500; color: #374151; }
    .search-item .info { font-size: 12px; color: #6b7280; margin-top: 2px; }
    
    .selected-customer { background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 16px; margin-top: 12px; }
    .selected-customer h4 { color: #166534; margin-bottom: 8px; }
    .selected-customer p { font-size: 13px; color: #374151; margin: 2px 0; }
    .selected-customer .remove-btn { float: right; background: #dc2626; color: #fff; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px; }
    
    .items-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    .items-table th, .items-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    .items-table th { background: #f9fafb; font-weight: 600; font-size: 13px; color: #374151; }
    .items-table td { vertical-align: middle; }
    .items-table input { width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; }
    .items-table input[type="number"] { width: 80px; }
    
    .totals-section { margin-top: 24px; display: flex; justify-content: flex-end; }
    .totals-box { width: 350px; background: #f9fafb; border-radius: 8px; padding: 20px; }
    .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
    .totals-row.total { border-top: 2px solid #e5e7eb; margin-top: 8px; padding-top: 16px; font-weight: 600; font-size: 16px; }
    .totals-row input { width: 120px; text-align: right; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; }
    
    .add-product-section { margin-top: 20px; padding: 20px; background: #f9fafb; border-radius: 8px; }
    .add-product-section h4 { margin-bottom: 16px; color: #374151; }
    .add-product-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 12px; align-items: end; }
    
    .product-specs { font-size: 11px; color: #6b7280; margin-top: 4px; }
    
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb; }
    
    .hidden { display: none; }
    
    @media (max-width: 768px) {
        .add-product-grid { grid-template-columns: 1fr; }
        .totals-section { justify-content: stretch; }
        .totals-box { width: 100%; }
    }
</style>
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
                    <label>Quotation Date *</label>
                    <input type="date" name="quotation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Valid Until</label>
                    <input type="date" name="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Selection -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2 style="font-size: 16px; color: #333; margin: 0;">Customer Information</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Search Customer by Name *</label>
                <div class="search-container">
                    <input type="text" id="customerSearch" class="form-control" placeholder="Type customer name to search...">
                    <div id="customerDropdown" class="search-dropdown"></div>
                </div>
                <input type="hidden" name="customer_id" id="customerId" required>
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
                            <label style="font-size: 12px; color: #6b7280;">Product</label>
                            <div id="selectedProductName" style="font-weight: 500; color: #374151;"></div>
                            <div id="selectedProductSpecs" class="product-specs"></div>
                            <input type="hidden" id="selectedProductId">
                            <input type="hidden" id="selectedProductType">
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
                            <th width="30%">Product</th>
                            <th width="15%">Type</th>
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
                        <input type="number" name="discount_percent" id="discountPercent" value="0" min="0" max="100" step="0.01" onchange="calculateTotals()">
                    </div>
                    <div class="totals-row">
                        <span>Discount Amount:</span>
                        <span id="discountAmountDisplay">₹0.00</span>
                    </div>
                    <div class="totals-row total">
                        <span>Grand Total:</span>
                        <span id="grandTotalDisplay">₹0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes & Actions -->
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>Notes / Terms & Conditions</label>
                <textarea name="notes" class="form-control" rows="4" placeholder="Add any notes, terms, or conditions..."></textarea>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">Create Quotation</button>
            </div>
        </div>
    </div>
</form>
@endsection

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

    customerSearch.addEventListener('input', function() {
        clearTimeout(customerSearchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            customerDropdown.classList.remove('active');
            return;
        }
        
        customerSearchTimeout = setTimeout(() => {
            fetch(`/api/customers/search?search=${encodeURIComponent(query)}`)
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
    }

    function removeCustomer() {
        document.getElementById('customerId').value = '';
        document.getElementById('selectedCustomer').classList.add('hidden');
    }

    // Product Search
    const productSearch = document.getElementById('productSearch');
    const productDropdown = document.getElementById('productDropdown');
    let productSearchTimeout;

    productSearch.addEventListener('input', function() {
        clearTimeout(productSearchTimeout);
        const query = this.value.trim();
        
        if (query.length < 1) {
            productDropdown.classList.remove('active');
            return;
        }
        
        productSearchTimeout = setTimeout(() => {
            fetch(`/api/products/search?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(products => {
                    if (products.length === 0) {
                        productDropdown.innerHTML = '<div class="search-item"><div class="info">No products found</div></div>';
                    } else {
                        productDropdown.innerHTML = products.map(p => {
                            const specs = p.specifications ? p.specifications.map(s => `${s.spec_name}: ${s.spec_value}${s.spec_unit || ''}`).join(' | ') : '';
                            return `
                                <div class="search-item" onclick='selectProduct(${JSON.stringify(p)})'>
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

    function selectProduct(product) {
        document.getElementById('selectedProductId').value = product.id;
        document.getElementById('selectedProductName').textContent = product.product_name;
        document.getElementById('selectedProductType').value = product.product_type || '';
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
        const productType = document.getElementById('selectedProductType').value;
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
            product_type: productType,
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
                        <input type="hidden" name="items[${index}][description]" value="">
                    </td>
                    <td>${item.product_type || 'N/A'}</td>
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
        const discountAmount = (subtotal * discountPercent) / 100;
        const grandTotal = subtotal - discountAmount;
        
        document.getElementById('subtotalDisplay').textContent = `₹${subtotal.toFixed(2)}`;
        document.getElementById('discountAmountDisplay').textContent = `₹${discountAmount.toFixed(2)}`;
        document.getElementById('grandTotalDisplay').textContent = `₹${grandTotal.toFixed(2)}`;
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container')) {
            customerDropdown.classList.remove('active');
            productDropdown.classList.remove('active');
        }
    });

    // Form validation
    document.getElementById('quotationForm').addEventListener('submit', function(e) {
        if (!document.getElementById('customerId').value) {
            e.preventDefault();
            alert('Please select a customer');
            return;
        }
        
        if (items.length === 0) {
            e.preventDefault();
            alert('Please add at least one product');
            return;
        }
    });
</script>
@endsection
