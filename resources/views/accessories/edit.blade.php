@extends('layouts.app')

@section('styles')
<style>
    .form-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 32px;
        max-width: 800px;
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
        min-height: 100px;
    }
    
    .form-helper {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
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
    
    .btn-danger {
        background: #fecaca;
        color: #991b1b;
        padding: 12px 28px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-left: auto;
    }
    
    .btn-danger:hover {
        background: #fca5a5;
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
    
    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <h1 class="form-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px; color: #2563eb;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit Accessory
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>✓ Success!</strong> {{ session('success') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('accessories.update', $accessory) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Accessory Name <span style="color: #ef4444;">*</span></label>
            <input type="text" name="name" id="name" class="form-control" 
                   placeholder="e.g., Hydraulic Pump, Digital Display, Safety Guard" 
                   value="{{ old('name', $accessory->name) }}" required>
            <div class="form-helper">Give this accessory a clear, descriptive name</div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" 
                      placeholder="Optional: Add details about this accessory">{{ old('description', $accessory->description) }}</textarea>
            <div class="form-helper">Optional description for reference</div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Product Notes</label>
            <textarea name="notes" id="notes" class="form-control" 
                      placeholder="e.g., POWER SUPPLY ; 400/440 VOLTS, 3 PHASE, 50 CYCLES.&#10;AS DAY IMPROVEMENTS ARE CONTEMPLATED FOR BETTER PERFORMANCE OF THE MACHINE.&#10;WE RESERVE THE RIGHTS TO ALTER THE SPECIFICATIONS WITHOUT PRIOR NOTICE.">{{ old('notes', $accessory->notes) }}</textarea>
            <div class="form-helper">Additional product notes and specifications to display in quotations</div>
        </div>

        <div class="form-group">
            <label for="is_active" class="form-label">Status</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active', $accessory->is_active) == 1 ? 'selected' : '' }}>✓ Active</option>
                <option value="0" {{ old('is_active', $accessory->is_active) == 0 ? 'selected' : '' }}>✗ Inactive</option>
            </select>
            <div class="form-helper">Only active accessories can be linked to products</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Accessory
            </button>
            <a href="{{ route('accessories.index') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cancel
            </a>
            <form method="POST" action="{{ route('accessories.destroy', $accessory) }}" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Are you sure? This cannot be undone.')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; margin-right: 8px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
