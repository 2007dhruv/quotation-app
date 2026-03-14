    @extends('layouts.app')

    @section('title', 'Edit Quotation - ' . $quotation->quotation_number)

    @section('styles')
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; color: #374151; margin-bottom: 6px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        
        .quotation-details { background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
        .quotation-details .row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 16px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table th, .items-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .items-table th { background: #f9fafb; font-weight: 600; font-size: 13px; color: #374151; }
        .items-table input { width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; }
        .items-table input:focus { outline: none; border-color: #2563eb; }
        
        .terms-checkbox-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px; }
        .term-checkbox-item { display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb; }
        .term-checkbox-item input[type="checkbox"] { margin-top: 4px; cursor: pointer; }
        .term-checkbox-item label { cursor: pointer; flex: 1; }
        .term-checkbox-item .title { font-weight: 500; color: #374151; }
        .term-checkbox-item .description { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .term-checkbox-item input[type="checkbox"]:checked + label { color: #065f46; }
        
        .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb; }
        .summary-box { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
        .summary-item { }
        .summary-item .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .summary-item .value { font-size: 18px; font-weight: 600; color: #374151; }
        .summary-item .value.total { color: #2563eb; font-size: 20px; }

        /* Quill Editor Styling */
        .ql-toolbar { border: 1px solid #d1d5db; border-bottom: none; border-radius: 6px 6px 0 0; background: #f9fafb; }
        .ql-container { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; font-size: 14px; }
        .ql-editor { min-height: 300px; padding: 15px; }
        .ql-editor.ql-blank::before { color: #9ca3af; }

        @media (max-width: 768px) {
            .quotation-details .row {
                grid-template-columns: 1fr;
            }
            .summary-box {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
            }
            .form-actions .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    @endsection

    @section('content')
    <form action="{{ route('quotations.update', $quotation) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-header">
                <div>
                    <h1>Edit Quotation - #{{ $quotation->quotation_number }}</h1>
                </div>
                <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-secondary">← Back to Quotation</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Quotation Details (Editable) -->
                <h3 style="margin-bottom: 20px; color: #374151;">Quotation Details</h3>
                <div class="quotation-details">
                    <div class="row">
                        <div class="form-group">
                            <label>Company *</label>
                            <select name="company_id" id="company_id" required onchange="updateQuotationNumber(); loadCompanyDefaultLetterBody();">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $quotation->company_id == $company->id ? 'selected' : '' }} data-short-name="{{ $company->company_short_name }}" data-default-letter="{{ base64_encode($company->default_letter_body ?? '') }}">
                                        {{ $company->company_name }} ({{ $company->company_short_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Customer *</label>
                            <select name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $quotation->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quotation Date *</label>
                            <input type="date" name="quotation_date" value="{{ $quotation->quotation_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Valid Until</label>
                            <input type="date" name="valid_until" value="{{ $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : '' }}">
                        </div>
                        <div class="form-group">
                            <label>Subject (Optional)</label>
                            <input class="form-control" type="text" name="subject" value="{{ $quotation->subject }}" placeholder="Leave blank for auto-generated subject">
                        </div>
                    </div>

                    <!-- Letter Body for PDF -->
                    <div class="form-group" style="margin-top: 20px;">
                        <label>Letter Body (Full Customizable Content)</label>
                        <div id="quotation_letter_body" style="border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; background: #fff;"></div>
                        <small class="text-muted">Leave blank to use default letter format. This text will replace the entire introduction and closing section in the PDF.</small>
                        <input type="hidden" id="quotation_letter_body_hidden" name="quotation_letter_body" value="{{ old('quotation_letter_body', $quotation->quotation_letter_body ?? '') }}">
                    </div>
                </div>

                <!-- Items - Editable -->
                <h3 style="margin-bottom: 16px; margin-top: 24px; color: #374151;">Items in Quotation</h3>
                <div style="overflow-x: auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Product</th>
                                <th width="15%">Qty</th>
                                <th width="22%">Unit Price (₹)</th>
                                <th width="23%">Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quotation->items as $index => $item)
                                <tr data-item-index="{{ $index }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->product_name }}</strong></td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" step="1" class="qty-input" onchange="calculateTotal()" data-item="{{ $index }}">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01" class="price-input" onchange="calculateTotal()" data-item="{{ $index }}">
                                    </td>
                                    <td>
                                        <span class="item-total" data-item="{{ $index }}">₹{{ number_format($item->total_price, 2) }}</span>
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #9ca3af;">No items in quotation</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Financial Summary -->
                <div class="summary-box">
                    <div class="summary-item">
                        <div class="label">Subtotal</div>
                        <div class="value" id="subtotal">₹{{ number_format($quotation->subtotal, 2) }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Discount (%)</div>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" name="discount_percent" value="{{ $quotation->discount_percent }}" min="0" max="100" step="0.01" style="width: 80px;" onchange="calculateTotal()">
                            <div class="value" id="discount-amount">₹{{ number_format($quotation->discount_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Tax (%)</div>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" name="tax_percent" value="{{ $quotation->tax_percent }}" min="0" max="100" step="0.01" style="width: 80px;" onchange="calculateTotal()">
                            <div class="value" id="tax-amount">₹{{ number_format($quotation->tax_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Grand Total</div>
                        <div class="value total" id="grand-total">₹{{ number_format($quotation->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Terms & Conditions - Editable -->
                <h3 style="margin-bottom: 16px; margin-top: 24px; color: #374151;">Terms & Conditions</h3>
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 16px;">Select which terms & conditions to include in the quotation PDF:</p>
                
                @if($termsConditions->count() > 0)
                    <div class="terms-checkbox-list">
                        @foreach($termsConditions as $term)
                            <div class="term-checkbox-item">
                                <input type="checkbox" 
                                    id="term_{{ $term->id }}" 
                                    name="terms_conditions[]" 
                                    value="{{ $term->id }}"
                                    {{ $quotation->termsConditions->contains($term->id) ? 'checked' : '' }}>
                                <label for="term_{{ $term->id }}">
                                    <div class="title">{{ $term->title }}</div>
                                    <div class="description">{{ substr($term->description, 0, 100) }}{{ strlen($term->description) > 100 ? '...' : '' }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 20px; background: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b; color: #92400e;">
                        No active terms & conditions available. <a href="{{ route('terms-conditions.index') }}" style="color: #b45309;">Create one</a>
                    </div>
                @endif

                <!-- Hidden inputs for calculated financial values -->
                <input type="hidden" name="subtotal" id="hidden-subtotal" value="{{ $quotation->subtotal }}">
                <input type="hidden" name="discount_amount" id="hidden-discount-amount" value="{{ $quotation->discount_amount }}">
                <input type="hidden" name="tax_amount" id="hidden-tax-amount" value="{{ $quotation->tax_amount }}">
                <input type="hidden" name="total_amount" id="hidden-total-amount" value="{{ $quotation->total_amount }}">

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    <script>
    function calculateTotal() {
        let subtotal = 0;
        
        // Calculate subtotal from items
        const itemRows = document.querySelectorAll('.items-table tbody tr[data-item-index]');
        itemRows.forEach((row) => {
            const qtyInput = row.querySelector('.qty-input');
            const priceInput = row.querySelector('.price-input');
            const itemIndex = row.dataset.itemIndex;
            
            if (qtyInput && priceInput) {
                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = qty * price;
                
                // Update item total display
                const itemTotalSpan = row.querySelector(`.item-total[data-item="${itemIndex}"]`);
                if (itemTotalSpan) {
                    itemTotalSpan.textContent = '₹' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                
                subtotal += total;
            }
        });
        
        // Get discount and tax percentages
        const discountPercent = parseFloat(document.querySelector('input[name="discount_percent"]').value) || 0;
        const taxPercent = parseFloat(document.querySelector('input[name="tax_percent"]').value) || 0;
        
        // Calculate amounts
        const discountAmount = (subtotal * discountPercent) / 100;
        const afterDiscount = subtotal - discountAmount;
        const taxAmount = (afterDiscount * taxPercent) / 100;
        const grandTotal = afterDiscount + taxAmount;
        
        // Update display
        document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('discount-amount').textContent = '₹' + discountAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('tax-amount').textContent = '₹' + taxAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('grand-total').textContent = '₹' + grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        // Update hidden input fields
        document.getElementById('hidden-subtotal').value = subtotal.toFixed(2);
        document.getElementById('hidden-discount-amount').value = discountAmount.toFixed(2);
        document.getElementById('hidden-tax-amount').value = taxAmount.toFixed(2);
        document.getElementById('hidden-total-amount').value = grandTotal.toFixed(2);
    }

    // Calculate totals on page load and add submit handler
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
        
        // Get form and hidden input references FIRST
        const form = document.querySelector('form');
        const hiddenInput = document.getElementById('quotation_letter_body_hidden');
        
        if (!form || !hiddenInput) {
            console.error('Form or hidden input not found!');
            return;
        }

        console.log('✓ Form and hidden input found');

        // Load company's default letter body
        function loadCompanyDefaultLetterBody() {
            const companySelect = document.getElementById('company_id');
            
            // Wait for Quill to be ready
            setTimeout(function() {
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

        // Initialize Quill editor for Letter Body
        var quill = new Quill('#quotation_letter_body', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
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

        console.log('✓ Quill editor initialized');

        // Set initial content from hidden input
        const savedContent = hiddenInput.value;
        if (savedContent && savedContent.trim().length > 0) {
            console.log('📝 Setting initial content, length:', savedContent.length);
            quill.root.innerHTML = savedContent;
        } else {
            console.log('No initial content found');
        }

        // CONTINUOUS SYNC: Use Quill's text-change event to keep hidden input updated
        quill.on('text-change', function(delta, oldDelta, source) {
            const quillHTML = quill.root.innerHTML;
            hiddenInput.value = quillHTML;
            console.log('📝 Auto-synced on text change, length:', quillHTML.length);
        });

        // Also manually sync on form submission (just to be safe)
        form.addEventListener('submit', function(e) {
            const finalContent = quill.root.innerHTML;
            hiddenInput.value = finalContent;
            
            console.log('🔴 FORM SUBMISSION');
            console.log('  Letter body content length:', finalContent.length);
            console.log('  Content preview:', finalContent.substring(0, 80));
            console.log('  Hidden input updated:', hiddenInput.value.substring(0, 80));
        });

        console.log('✓✓✓ All Quill handlers attached');
    });
    </script>
    @endsection
