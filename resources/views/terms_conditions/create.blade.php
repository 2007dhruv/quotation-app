@extends('layouts.app')

@section('styles')
<style>
    .form-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 32px;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .form-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: #f0f7ff;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 150px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }
    
    .form-helper {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        padding: 12px 28px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }
    
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
        padding: 12px 28px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #d1d5db;
        transform: translateY(-2px);
    }
    
    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    
    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-danger li {
        margin: 4px 0;
    }
    
    @media (max-width: 768px) {
        .form-container {
            padding: 24px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <h1 class="form-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px; color: #2563eb;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add New Terms & Condition
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('terms-conditions.store') }}">
        @csrf

        <div class="form-group">
            <label for="title" class="form-label">Title <span style="color: #ef4444;">*</span></label>
            <input type="text" name="title" id="title" class="form-control" 
                   placeholder="e.g., PRICE, WARRANTY, JURISDICTION, COMMISSIONING" 
                   value="{{ old('title') }}" required>
            <div class="form-helper">The heading that will appear in PDF</div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description <span style="color: #ef4444;">*</span></label>
            <textarea name="description" id="description" class="form-control" 
                      placeholder="Enter the full terms and conditions text. You can use multiple lines."
                      required>{{ old('description') }}</textarea>
            <div class="form-helper">The complete T&C text that will display in the PDF</div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="display_order" class="form-label">Display Order <span style="color: #ef4444;">*</span></label>
                <input type="number" name="display_order" id="display_order" class="form-control" min="0"
                       value="{{ old('display_order', 0) }}" required>
                <div class="form-helper">Lower numbers appear first (0, 1, 2...)</div>
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>✓ Active</option>
                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>✗ Inactive</option>
                </select>
                <div class="form-helper">Only active T&C appear in PDFs</div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Terms & Condition
            </button>
            <a href="{{ route('terms-conditions.index') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
