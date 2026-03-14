@extends('layouts.app')

@section('title', 'Edit Company - Quotation App')

@section('styles')
    <style>
        .form-container {
            max-width: 900px;
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

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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

        .image-preview-container {
            margin-top: 12px;
        }

        .image-preview {
            max-width: 150px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        .image-preview-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        textarea {
            resize: vertical;
        }

        /* Quill Editor Styling - MATCH QUOTATION FORM EXACTLY */
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

        #default_letter_body_editor {
            margin-top: 8px;
        }

        @media (max-width: 768px) {

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

@endsection

@section('content')
    <div class="form-container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Company</h1>
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

                <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Company Basic Information -->
                    <h3 class="section-title">Company Information</h3>

                    <div class="form-group">
                        <label for="company_name">Company Name <span>*</span></label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                            value="{{ old('company_name', $company->company_name) }}" required
                            placeholder="Enter company name">
                    </div>

                    <div class="form-group">
                        <label for="company_short_name">Company Short Name (for Quotation) <span>*</span></label>
                        <input type="text" class="form-control" id="company_short_name" name="company_short_name"
                            value="{{ old('company_short_name', $company->company_short_name) }}" required
                            placeholder="e.g., AMT (used in quotation number format)" maxlength="5">
                        <small style="color: #6b7280;">This short name will be used in quotation numbers (e.g.,
                            AMT/02/2026/0001)</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email <span>*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $company->email) }}" required placeholder="company@example.com">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number <span>*</span></label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $company->phone_number) }}" required
                                placeholder="+91 9876543210">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" class="form-control" id="website" name="website"
                            value="{{ old('website', $company->website) }}" placeholder="https://example.com">
                    </div>

                    <div class="form-group">
                        <label for="gst_number">GST Number</label>
                        <input type="text" class="form-control" id="gst_number" name="gst_number"
                            value="{{ old('gst_number', $company->gst_number) }}" placeholder="24AADFA5082H1ZP">
                    </div>

                    <div class="form-group">
                        <label for="company_description">Company Description</label>
                        <textarea class="form-control" id="company_description" name="company_description" rows="3"
                            placeholder="Brief description about your company...">{{ old('company_description', $company->company_description) }}</textarea>
                    </div>

                    <!-- Address Information -->
                    <h3 class="section-title">Address Information</h3>

                    <div class="form-group">
                        <label for="address">Address <span>*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="2" required
                            placeholder="Street address...">{{ old('address', $company->address) }}</textarea>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="city">City <span>*</span></label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ old('city', $company->city) }}" required placeholder="Rajkot">
                        </div>
                        <div class="form-group">
                            <label for="state">State <span>*</span></label>
                            <input type="text" class="form-control" id="state" name="state"
                                value="{{ old('state', $company->state) }}" required placeholder="Gujarat">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code <span>*</span></label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code"
                                value="{{ old('postal_code', $company->postal_code) }}" required placeholder="360004">
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <h3 class="section-title">Bank Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name"
                                value="{{ old('bank_name', $company->bank_name) }}" placeholder="KOTAK BANK">
                        </div>
                        <div class="form-group">
                            <label for="bank_branch">Branch</label>
                            <input type="text" class="form-control" id="bank_branch" name="bank_branch"
                                value="{{ old('bank_branch', $company->bank_branch) }}" placeholder="Kalawad Road Branch">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="account_holder_name">Account Holder Name</label>
                            <input type="text" class="form-control" id="account_holder_name" name="account_holder_name"
                                value="{{ old('account_holder_name', $company->account_holder_name) }}"
                                placeholder="Company Name">
                        </div>
                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                value="{{ old('account_number', $company->account_number) }}" placeholder="4712622406">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text" class="form-control" id="ifsc_code" name="ifsc_code"
                                value="{{ old('ifsc_code', $company->ifsc_code) }}" placeholder="KKBK0002794">
                        </div>
                        <div class="form-group">
                            <label for="account_type">Account Type</label>
                            <select class="form-control" id="account_type" name="account_type">
                                <option value="">Select Account Type</option>
                                <option value="Current" {{ old('account_type', $company->account_type) == 'Current' ? 'selected' : '' }}>Current</option>
                                <option value="Savings" {{ old('account_type', $company->account_type) == 'Savings' ? 'selected' : '' }}>Savings</option>
                                <option value="Business" {{ old('account_type', $company->account_type) == 'Business' ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                    </div>

                    <!-- Default Letter Body for Quotations -->
                    <div class="form-group" style="margin-top: 20px; margin-bottom: 24px;">
                        <label>Default Letter Body for Quotations</label>
                        <div id="default_letter_body_editor" style="min-height:150px; max-height:300px; overflow-y:auto;">
                        </div>
                        <small class="text-muted"
                            style="display: block; margin-top: 8px; color: #6b7280; font-size: 12px;">Set a default letter
                            to be automatically loaded when creating quotations for this company. Users can customize it per
                            quotation.</small>
                        <input type="hidden" id="default_letter_body" name="default_letter_body" value="">
                    </div>

                    <!-- Images & Logos -->
                    <h3 class="section-title">Company Logos & Images</h3>

                    <!-- Company Logo -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="logo_path">Company Logo</label>
                            @if($company->logo_path)
                                <div class="image-preview-container">
                                    <div class="image-preview-label">Current Logo:</div>
                                    @php
                                        $logoPath = $company->logo_path;
                                        if (strpos($logoPath, 'storage/') === 0) {
                                            $logoPath = substr($logoPath, 8);
                                        }
                                    @endphp
                                    <img src="/get-image/{{ $logoPath }}" alt="Company Logo" class="image-preview">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="logo_path" name="logo_path" accept="image/*"
                                style="margin-top: 8px;">
                            <small style="color: #6b7280;">JPG, PNG, GIF (Max 2MB) - Leave empty to keep current</small>
                        </div>

                    </div>



                    <div class="form-group">
                        <label for="signature_image_path">Signature Image (Authorized Signatory)</label>
                        @if($company->signature_image_path)
                            <div class="image-preview-container">
                                <div class="image-preview-label">Current Signature:</div>
                                @php
                                    $signaturePath = $company->signature_image_path;
                                    if (strpos($signaturePath, 'storage/') === 0) {
                                        $signaturePath = substr($signaturePath, 8);
                                    }
                                @endphp
                                <img src="/get-image/{{ $signaturePath }}" alt="Signature" class="image-preview">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="signature_image_path" name="signature_image_path"
                            accept="image/*" style="margin-top: 8px;">
                        <small style="color: #6b7280;">JPG, PNG, GIF (Max 2MB) - Leave empty to keep current</small>
                    </div>
            </div>



            <!-- Active Status -->
            <div class="form-group" style="margin-top: 24px;">
                <label style="display: flex; align-items: center; cursor: pointer; font-weight: 400;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }} style="margin-right: 8px;">
                    <span>Set as Active Company</span>
                </label>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update Company</button>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Quill editor for default letter body - EXACT MATCH TO QUOTATION FORM
            window.defaultLetterBodyQuill = new Quill('#default_letter_body_editor', {
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
                placeholder: 'Enter the default letter body that will be used for all quotations under this company...',
                formats: ['bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block', 'header', 'indent', 'list', 'script', 'align', 'size', 'header', 'color', 'background', 'font', 'link', 'image']
            });

            console.log('✓ Quill editor initialized for Company EDIT form');

            // LOAD EXISTING CONTENT
            const existingContent = {!! json_encode($company->default_letter_body ?? '') !!};
            if (existingContent && existingContent.trim() !== '') {
                window.defaultLetterBodyQuill.root.innerHTML = existingContent;
                console.log('📄 Loaded existing default letter body');
            }

            // CONTINUOUS SYNC: Use text-change event to keep hidden input updated in real-time
            const hiddenInput = document.getElementById('default_letter_body');
            hiddenInput.value = existingContent;

            window.defaultLetterBodyQuill.on('text-change', function (delta, oldDelta, source) {
                const quillHTML = window.defaultLetterBodyQuill.root.innerHTML;
                hiddenInput.value = quillHTML;
                console.log('📝 EDIT form: Auto-synced on text change, length:', quillHTML.length);
            });

            // Attach form submission handler
            const companyForm = document.querySelector('form');
            if (companyForm) {
                companyForm.addEventListener('submit', function (e) {
                    // SYNC QUILL CONTENT (backup sync in case text-change didn't catch it)
                    const quillContent = window.defaultLetterBodyQuill.root.innerHTML;
                    hiddenInput.value = quillContent;
                    console.log('💾 EDIT form: Synced on form submission, length:', quillContent.length);
                });
            }
        });
    </script>
@endsection