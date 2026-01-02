@extends('layouts.app')

@section('title', 'Add Customer - Quotation App')

@section('styles')
<style>
    .form-container { max-width: 600px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px; }
    .form-group label span { color: #dc2626; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    textarea.form-control { resize: vertical; min-height: 80px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .btn-group { display: flex; gap: 12px; margin-top: 24px; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h1>Add New Customer</h1>
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

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="customer_name">Customer Name <span>*</span></label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                           value="{{ old('customer_name') }}" required placeholder="Enter customer name">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" 
                              placeholder="Enter full address">{{ old('address') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="{{ old('city') }}" placeholder="Enter city">
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" 
                               value="{{ old('state') }}" placeholder="Enter state">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gst_no">GST Number</label>
                    <input type="text" class="form-control" id="gst_no" name="gst_no" 
                           value="{{ old('gst_no') }}" placeholder="Enter GST number">
                </div>

                <div class="form-group">
                    <label for="gst_type">GST Type <span>*</span></label>
                    <select class="form-control" id="gst_type" name="gst_type" required>
                        <option value="">-- Select GST Type --</option>
                        <option value="instate" {{ old('gst_type') == 'instate' ? 'selected' : '' }}>In-State (CGST + SGST 9% + 9%)</option>
                        <option value="outofstate" {{ old('gst_type') == 'outofstate' ? 'selected' : '' }}>Out-of-State (IGST 18%)</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile">Mobile <span>*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" 
                               value="{{ old('mobile') }}" required placeholder="Enter mobile number">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email') }}" placeholder="Enter email address">
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
