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
    .form-control.is-invalid { border-color: #dc2626; }
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
                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" 
                           value="{{ old('customer_name') }}" required placeholder="Enter customer name">
                    @error('customer_name')
                        <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" 
                              placeholder="Enter full address">{{ old('address') }}</textarea>
                    @error('address')
                        <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" 
                               value="{{ old('city') }}" placeholder="Enter city">
                        @error('city')
                            <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="state">State <span>*</span></label>
                        <select class="form-control @error('state') is-invalid @enderror" id="state" name="state" required onchange="updateGSTType()">
                            <option value="">-- Select State --</option>
                            <option value="Andaman and Nicobar Islands" {{ old('state') == 'Andaman and Nicobar Islands' ? 'selected' : '' }}>Andaman and Nicobar Islands</option>
                            <option value="Andhra Pradesh" {{ old('state') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                            <option value="Arunachal Pradesh" {{ old('state') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                            <option value="Assam" {{ old('state') == 'Assam' ? 'selected' : '' }}>Assam</option>
                            <option value="Bihar" {{ old('state') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                            <option value="Chandigarh" {{ old('state') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                            <option value="Chhattisgarh" {{ old('state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                            <option value="Dadra and Nagar Haveli and Daman and Diu" {{ old('state') == 'Dadra and Nagar Haveli and Daman and Diu' ? 'selected' : '' }}>Dadra and Nagar Haveli and Daman and Diu</option>
                            <option value="Delhi" {{ old('state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Goa" {{ old('state') == 'Goa' ? 'selected' : '' }}>Goa</option>
                            <option value="Gujarat" {{ old('state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                            <option value="Haryana" {{ old('state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                            <option value="Himachal Pradesh" {{ old('state') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                            <option value="Jharkhand" {{ old('state') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                            <option value="Karnataka" {{ old('state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                            <option value="Kerala" {{ old('state') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                            <option value="Lakshadweep" {{ old('state') == 'Lakshadweep' ? 'selected' : '' }}>Lakshadweep</option>
                            <option value="Madhya Pradesh" {{ old('state') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                            <option value="Maharashtra" {{ old('state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                            <option value="Manipur" {{ old('state') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                            <option value="Meghalaya" {{ old('state') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                            <option value="Mizoram" {{ old('state') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                            <option value="Nagaland" {{ old('state') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                            <option value="Odisha" {{ old('state') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                            <option value="Puducherry" {{ old('state') == 'Puducherry' ? 'selected' : '' }}>Puducherry</option>
                            <option value="Punjab" {{ old('state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Rajasthan" {{ old('state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                            <option value="Sikkim" {{ old('state') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                            <option value="Tamil Nadu" {{ old('state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                            <option value="Telangana" {{ old('state') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                            <option value="Tripura" {{ old('state') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                            <option value="Uttar Pradesh" {{ old('state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                            <option value="Uttarakhand" {{ old('state') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                            <option value="West Bengal" {{ old('state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                        </select>
                        @error('state')
                            <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="pin_code">Pin Code</label>
                    <input type="text" class="form-control @error('pin_code') is-invalid @enderror" id="pin_code" name="pin_code" 
                           value="{{ old('pin_code') }}" placeholder="Enter pin code (6 digits)">
                    @error('pin_code')
                        <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gst_no">GST Number</label>
                    <input type="text" class="form-control @error('gst_no') is-invalid @enderror" id="gst_no" name="gst_no" 
                           value="{{ old('gst_no') }}" placeholder="Enter GST number (e.g., 27AABCU9603R1Z5)">
                    @error('gst_no')
                        <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gst_type">GST Type <span>*</span></label>
                    <select class="form-control @error('gst_type') is-invalid @enderror" id="gst_type" name="gst_type" required>
                        <option value="">-- Select GST Type --</option>
                        <option value="instate" {{ old('gst_type') == 'instate' ? 'selected' : '' }}>In-State (CGST + SGST 9% + 9%)</option>
                        <option value="outofstate" {{ old('gst_type') == 'outofstate' ? 'selected' : '' }}>Out-of-State (IGST 18%)</option>
                    </select>
                    @error('gst_type')
                        <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile">Mobile <span>*</span></label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" 
                               value="{{ old('mobile') }}" required placeholder="Enter mobile number (10 digits)">
                        @error('mobile')
                            <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                               value="{{ old('email') }}" placeholder="Enter email address">
                        @error('email')
                            <small style="color: #dc2626; display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
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

<script>
    function updateGSTType() {
        const stateSelect = document.getElementById('state');
        const gstTypeSelect = document.getElementById('gst_type');
        const selectedState = stateSelect.value;
        
        // Hindi company is in Gujarat - so any customer in Gujarat is In-State (CGST + SGST)
        if (selectedState === 'Gujarat') {
            gstTypeSelect.value = 'instate';
            gstTypeSelect.title = 'Automatically set to In-State (Same State as Company)';
        } else if (selectedState !== '') {
            gstTypeSelect.value = 'outofstate';
            gstTypeSelect.title = 'Automatically set to Out-of-State';
        } else {
            gstTypeSelect.value = '';
            gstTypeSelect.title = 'Select a state to auto-update';
        }
    }
    
    // Initialize on page load (for old values)
    window.addEventListener('load', function() {
        updateGSTType();
    });
</script>
@endsection
