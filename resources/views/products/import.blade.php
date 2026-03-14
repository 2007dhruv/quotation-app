@extends('layouts.app')

@section('title', 'Import Products - Quotation App')

@section('styles')
<style>
    .import-container { max-width: 900px; margin: 0 auto; }
    
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
    .nav-breadcrumb a { color: #667eea; text-decoration: none; font-weight: 600; }
    .nav-breadcrumb a:hover { text-decoration: underline; }
    .nav-breadcrumb span { color: #6b7280; }
    
    .import-card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 32px; }
    .import-section { margin-bottom: 32px; }
    .import-section h3 { font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; }
    .import-section p { color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 12px; }
    
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 32px;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .upload-area:hover {
        border-color: #667eea;
        background: #f3f4f6;
    }
    .upload-area.drag-over {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .upload-icon { font-size: 48px; margin-bottom: 16px; }
    .upload-text { font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
    .upload-subtext { font-size: 13px; color: #6b7280; }
    
    .csv-input { display: none; }
    
    .btn-group { display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
    .btn-primary, .btn-secondary { padding: 12px 24px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(102,126,234,0.4); }
    .btn-secondary { background: #6b7280; color: white; }
    .btn-secondary:hover { background: #4b5563; }
    .btn-secondary-light { background: white; color: #667eea; border: 2px solid #667eea; }
    .btn-secondary-light:hover { background: #f9fafb; }
    
    .error-box { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 16px; margin: 16px 0; }
    .error-title { color: #dc2626; font-weight: 700; margin-bottom: 8px; }
    .error-item { color: #991b1b; font-size: 13px; margin: 4px 0; }
    
    .success-box { background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 6px; padding: 16px; margin: 16px 0; }
    .success-title { color: #059669; font-weight: 700; }
    
    .template-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
    .template-table th, .template-table td { padding: 12px; text-align: left; border: 1px solid #e5e7eb; }
    .template-table th { background: #f3f4f6; font-weight: 700; color: #1f2937; }
    .template-table td { color: #6b7280; font-size: 13px; }
    .template-table code { background: #f3f4f6; padding: 4px 8px; border-radius: 4px; color: #dc2626; }
    
    .field-list { margin: 16px 0; padding-left: 24px; }
    .field-list li { margin: 8px 0; color: #6b7280; font-size: 14px; }
    .field-list strong { color: #1f2937; }
    
    .file-selected { background: #d1fae5; border-color: #6ee7b7; padding: 16px; border-radius: 6px; margin-top: 12px; }
    .file-selected strong { color: #059669; }
    
    @media (max-width: 768px) {
        .import-card { padding: 20px; }
        .btn-group { flex-direction: column; }
        .btn-group .btn-primary, .btn-group .btn-secondary { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="nav-breadcrumb">
    <span>📦 Product Masters</span>
    <span>/</span>
    <a href="{{ route('master.index') }}">Masters List</a>
    <span>/</span>
    <span style="color: #374151; font-weight: 600;">CSV Import</span>
</div>

<div class="import-container">
    <div class="import-card">
        <!-- Success Message -->
        @if(session('success'))
            <div class="success-box">
                <div class="success-title">{{ session('success') }}</div>
                @if(session('errors') && count(session('errors')) > 0)
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #6ee7b7;">
                        <strong style="color: #dc2626;">⚠️ Warnings:</strong>
                        @foreach(session('errors') as $error)
                            <div style="color: #6b4423; font-size: 13px; margin: 4px 0;">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="error-box">
                <div class="error-title">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="import-section">
            <h3>📥 Import Products from CSV</h3>
            <p>Bulk import product masters and variants from a CSV file. This is the fastest way to add multiple products at once.</p>
        </div>

        <!-- File Upload Form -->
        <form action="{{ route('master.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="import-section">
                <h3>Step 1: Select CSV File</h3>
                
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('csvFile').click()">
                    <div class="upload-icon">📄</div>
                    <div class="upload-text">Click to select or drag CSV file here</div>
                    <div class="upload-subtext">Max file size: 5MB</div>
                </div>

                <input type="file" id="csvFile" name="csv_file" class="csv-input" accept=".csv,.txt" required>

                @if($errors && is_object($errors) && method_exists($errors, 'has') && $errors->has('csv_file'))
                    <div class="error-box" style="margin-top: 12px;">
                        <div class="error-item">{{ $errors->first('csv_file') }}</div>
                    </div>
                @endif

                <div id="fileSelected" class="file-selected" style="display: none;">
                    <strong style="color: #059669;">✓ File selected:</strong> <span id="fileName"></span>
                </div>
            </div>

            <!-- CSV Format Guide -->
            <div class="import-section">
                <h3>Step 2: CSV Format Guide</h3>
                <p>Your CSV file must have these columns in the first row (header):</p>

                <table class="template-table">
                    <tr>
                        <th>Column Name</th>
                        <th>Required</th>
                        <th>Description</th>
                        <th>Example</th>
                    </tr>
                    <tr>
                        <td><code>product_name</code></td>
                        <td><strong style="color: #dc2626;">Yes</strong></td>
                        <td>Main product name (category)</td>
                        <td>Hydraulic Shearing Machine</td>
                    </tr>
                    <tr>
                        <td><code>product_model</code></td>
                        <td><strong style="color: #dc2626;">Yes</strong></td>
                        <td>Product model/variant code</td>
                        <td>HM-2500, HM-3000</td>
                    </tr>
                    <tr>
                        <td><code>spec_name</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Specification name</td>
                        <td>Max Cutting Length</td>
                    </tr>
                    <tr>
                        <td><code>spec_value</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Specification value</td>
                        <td>2500</td>
                    </tr>
                    <tr>
                        <td><code>spec_unit</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Unit of measurement</td>
                        <td>mm, Ton, SPM</td>
                    </tr>
                    <tr>
                        <td><code>price</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Product price</td>
                        <td>150000</td>
                    </tr>
                    <tr>
                        <td><code>standard_accessories</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Accessories separated by semicolon</td>
                        <td>Pump;Control Panel;Cylinder</td>
                    </tr>
                    <tr>
                        <td><code>optional_accessories</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Optional accessories separated by semicolon</td>
                        <td>Digital Display;Remote Control</td>
                    </tr>
                    <tr>
                        <td><code>note</code></td>
                        <td><strong style="color: #6b7280;">No</strong></td>
                        <td>Product notes/description</td>
                        <td>Heavy duty industrial machine</td>
                    </tr>
                </table>

                <p><strong>📌 Important Notes:</strong></p>
                <ul class="field-list">
                    <li><strong>Accessories format:</strong> Use semicolon (;) to separate multiple items: <code>Pump;Control Panel;Cylinder</code></li>
                    <li><strong>Duplicate handling:</strong> If product_name already exists, it will be updated with new accessories/notes</li>
                    <li><strong>Price format:</strong> Use numbers only (no currency symbols): <code>150000</code></li>
                    <li><strong>Comma in values:</strong> If your data contains commas, wrap the value in quotes: <code>"Item 1, Item 2"</code></li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-primary">📤 Import CSV File</button>
                <a href="{{ asset('templates/products_import_template.csv') }}" download class="btn-secondary-light">📥 Download Sample Template</a>
                <a href="{{ route('master.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const csvFile = document.getElementById('csvFile');
    const fileSelected = document.getElementById('fileSelected');
    const fileName = document.getElementById('fileName');

    // Click upload area to select file
    uploadArea.addEventListener('click', () => csvFile.click());

    // Handle file selection
    csvFile.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            fileSelected.style.display = 'block';
        }
    });

    // Handle drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            csvFile.files = files;
            const file = files[0];
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            fileSelected.style.display = 'block';
        }
    });

    // Form submission
    document.getElementById('importForm').addEventListener('submit', (e) => {
        if (!csvFile.files.length) {
            e.preventDefault();
            alert('Please select a CSV file');
        }
    });
</script>
@endsection
