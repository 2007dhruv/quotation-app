@extends('layouts.app')

@section('title', 'Import Customers - Quotation App')

@section('styles')
<style>
    .import-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .import-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 32px;
        margin-bottom: 20px;
    }

    .import-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .import-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px 0;
    }

    .import-header p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 32px;
        text-align: center;
        margin-bottom: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .upload-area:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .upload-area.dragover {
        border-color: #2563eb;
        background: #dbeafe;
    }

    .upload-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .upload-text {
        color: #374151;
        font-weight: 500;
        font-size: 16px;
        margin: 0 0 4px 0;
    }

    .upload-subtext {
        color: #9ca3af;
        font-size: 13px;
        margin: 0;
    }

    #xlsx_file {
        display: none;
    }

    .file-input-wrapper {
        position: relative;
    }

    .selected-file {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 16px;
        color: #166534;
        font-size: 13px;
        display: none;
    }

    .selected-file.active {
        display: block;
    }

    .selected-file-name {
        font-weight: 600;
    }

    .instructions {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #78350f;
    }

    .instructions h3 {
        margin: 0 0 8px 0;
        font-weight: 600;
        color: #92400e;
    }

    .instructions ul {
        margin: 0;
        padding-left: 20px;
    }

    .instructions li {
        margin: 6px 0;
    }

    .form-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn-download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .alert {
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .import-stats {
        background: white;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .stat-box {
        padding: 12px;
        border-radius: 6px;
        text-align: center;
    }

    .stat-box.success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
    }

    .stat-box.warning {
        background: #fef3c7;
        border: 1px solid #fcd34d;
    }

    .stat-number {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    .error-list {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 12px;
        margin-top: 12px;
        max-height: 300px;
        overflow-y: auto;
    }

    .error-list ul {
        margin: 0;
        padding-left: 20px;
        list-style-type: disc;
    }

    .error-list li {
        color: #991b1b;
        font-size: 12px;
        margin: 6px 0;
    }

    .progress-info {
        font-size: 12px;
        color: #6b7280;
        text-align: center;
        margin-top: 12px;
    }

    @media (max-width: 600px) {
        .import-card {
            padding: 20px;
        }

        .upload-area {
            padding: 20px;
        }

        .import-stats {
            grid-template-columns: 1fr;
        }

        .form-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="import-container">
    <div class="import-card">
        <div class="import-header">
            <h1>📊 Import Customers</h1>
            <p>Upload an XLSX file to import multiple customers at once</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
                @if(session('import_stats'))
                    <div class="import-stats" style="margin-top: 12px;">
                        <div class="stat-box success">
                            <p class="stat-number">{{ session('import_stats.imported') }}</p>
                            <p class="stat-label">Imported</p>
                        </div>
                        <div class="stat-box warning">
                            <p class="stat-number">{{ session('import_stats.skipped') }}</p>
                            <p class="stat-label">Skipped</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
                @if(session('errors') && count(session('errors')) > 0)
                    <div class="error-list">
                        <ul>
                            @foreach(session('errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('import_stats'))
                    <div class="import-stats" style="margin-top: 12px;">
                        <div class="stat-box success">
                            <p class="stat-number">{{ session('import_stats.imported') }}</p>
                            <p class="stat-label">Imported</p>
                        </div>
                        <div class="stat-box warning">
                            <p class="stat-number">{{ session('import_stats.skipped') }}</p>
                            <p class="stat-label">Skipped</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="instructions">
            <h3>📋 Import Instructions:</h3>
            <ul>
                <li><strong>Required columns:</strong> Customer Name, Mobile, GST Type</li>
                <li><strong>Optional columns:</strong> Address, City, State, Pin Code, GST No, Email</li>
                <li><strong>GST Type values:</strong> "instate" or "outofstate"</li>
                <li><strong>Phone format:</strong> 10 digits without spaces or special characters</li>
                <li><strong>File size limit:</strong> 5MB maximum</li>
                <li><strong>Empty rows:</strong> Will be automatically skipped</li>
                <li><strong>📥 Download the sample template below to see the correct format</strong></li>
            </ul>
        </div>

        <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="file-input-wrapper">
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📁</div>
                    <p class="upload-text">Click to select or drag & drop XLSX file</p>
                    <p class="upload-subtext">Maximum file size: 5MB</p>
                </div>
                <input type="file" id="xlsx_file" name="xlsx_file" accept=".xlsx,.xls" required>
                <div class="selected-file" id="selectedFile">
                    <span class="selected-file-name" id="fileName"></span>
                    <span> - <a href="#" style="cursor: pointer; color: #dc2626;" onclick="clearFile(event)">Clear</a></span>
                </div>
            </div>

            @if($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->has('xlsx_file'))
                <div class="alert alert-error">
                    {{ $errors->first('xlsx_file') }}
                </div>
            @endif

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">📤 Import Customers</button>
                <a href="{{ route('customers.download-template') }}" class="btn btn-download">📥 Download Template</a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">← Back</a>
            </div>

            <div class="progress-info">
                ℹ️ The import process will validate all data. Invalid rows will be reported but won't prevent other rows from being imported.
            </div>
        </form>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('xlsx_file');
    const selectedFile = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');

    // Handle click on upload area
    uploadArea.addEventListener('click', () => fileInput.click());

    // Handle file selection
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            selectedFile.classList.add('active');
        }
    });

    // Handle drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            const file = e.dataTransfer.files[0];
            fileName.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            selectedFile.classList.add('active');
        }
    });

    function clearFile(event) {
        event.preventDefault();
        fileInput.value = '';
        selectedFile.classList.remove('active');
        fileName.textContent = '';
    }

    // Form submission prevents default if no file selected
    document.getElementById('importForm').addEventListener('submit', (e) => {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to import.');
        }
    });
</script>
@endsection
