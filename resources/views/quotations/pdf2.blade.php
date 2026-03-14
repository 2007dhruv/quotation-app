@php
    $company = $quotation->company ?? \App\Models\Company::getPrimary();
    $termsConditions = \App\Models\TermsCondition::getActive();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @page { size: A4; margin: 50mm 12mm 25mm 12mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9pt; color: #000; line-height: 1.2; margin: 0; padding: 0; }
        table { border-collapse: collapse; }
    </style>
</head>
<body>

<!-- FIXED HEADER (appears on all pages) -->
<div style="position: fixed; top: -45mm; left: 0; right: 0; height: 27mm; border-bottom: 2px solid #333; background: #fff; padding: 8px 5mm; z-index: 100;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <!-- LOGO -->
            <td width="20%" valign="middle">
                @if($company && isset($company->logo_file_path) && file_exists($company->logo_file_path))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($company->logo_file_path)) }}" alt="Company Logo"
                         style="width:120px; height:auto; margin-right:15px;">
                @elseif(file_exists(public_path('images/logo_alfa.jpeg')))
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/logo_alfa.jpeg'))) }}" alt="Logo"
                         style="width:120px; height:auto; margin-right:15px;">
                @endif
            </td>

            <!-- COMPANY DETAILS -->
            <td width="80%" valign="middle">
                <h2 style="margin:0; font-size:20px;">{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</h2>
                <p style="margin:2px 0; font-size:10px; line-height:1.3;">
                    @if($company && $company->company_description)
                        {{ $company->company_description }}<br>
                    @else
                        Manufacturer & Exporter of Engineering Machinery<br>
                        Hydraulic & Pneumatic Power Press, Press Brake, Shearing Machine<br>
                    @endif
                    <!-- @if($company && ($company->city || $company->state))
                        {{ $company->address ?? '' }}{{ ($company->address && ($company->city || $company->state)) ? ', ' : '' }}{{ $company->city }}{{ ($company->city && $company->state) ? ', ' : '' }}{{ $company->state }}
                    @else
                        Rajkot Industrial Area, Gujarat, India
                    @endif -->
                </p>
            </td>
        </tr>
    </table>
</div>

<!-- PDF FIRST PAGE CONTENT -->


<!-- CUSTOMER & QUOTATION INFO -->
<table width="100%" cellpadding="4" cellspacing="2" style="font-size:14px; margin-top: -50px ;">
    <tr>
        <!-- TO SECTION -->
        <td width="65%" valign="top">
            <strong>To,</strong><br>
            <strong>{{ $quotation->customer->customer_name }}</strong><br>
            @if($quotation->customer->address){{ $quotation->customer->address }}<br>@endif
            @if($quotation->customer->city || $quotation->customer->state){{ $quotation->customer->city }}{{ $quotation->customer->city && $quotation->customer->state ? ', ' : '' }}{{ $quotation->customer->state }}<br>@endif
            @if($quotation->customer->pin_code)<strong>PIN Code:</strong> {{ $quotation->customer->pin_code }}<br>@endif
            @if($quotation->customer->mobile)<strong>Mobile No:</strong> {{ $quotation->customer->mobile }}<br>@endif
        <!--   @if($quotation->customer->email)<strong>Email:</strong> {{ $quotation->customer->email }}<br>@endif -->
            @if($quotation->customer->gst_no)<strong>GSTIN:</strong> {{ $quotation->customer->gst_no }}@endif 
        </td>

        <!-- QUOTATION INFO -->
        <td width="35%" valign="top" align="right">
            <table width="100%" cellpadding="3" cellspacing="0">
                <tr>
                    <td align="right" nowrap><strong>Quote No. :</strong></td>
                    <td align="left" nowrap>{{ $quotation->quotation_number }}</td>
                </tr>
                <tr>
                    <td align="right" nowrap><strong>Date :</strong></td>
                    <td align="left" nowrap>{{ $quotation->quotation_date->format('d-m-Y') }}</td>
                </tr>
                @if($quotation->valid_until)
                <tr>
                    <td align="right" nowrap><strong>Valid Until :</strong></td>
                    <td align="left" nowrap>{{ $quotation->valid_until->format('d-m-Y') }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
<br>
    <h3>&ensp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SUBJECT : 
        @if($quotation->subject)
            {{ mb_strtoupper($quotation->subject) }}
        @else
            QUOTATION FOR {{ $quotation->items->count() > 0 ? mb_strtoupper($quotation->items->first()->product_name) : 'PRODUCTS/SERVICES' }}
            @if($quotation->items->count() > 1)
                &amp; {{ $quotation->items->count() - 1 }} MORE
            @endif
        @endif
    </strong></h3>  
    <h4 style="font: size 14px;">DEAR SIR ,</h4>
    
    @php
        // Check if letter body has actual content (not just empty HTML tags)
        $letterBody = $quotation->quotation_letter_body ?? '';
        $hasLetterBody = !empty($letterBody) && trim(strip_tags($letterBody)) !== '';
    @endphp
    
    @if($hasLetterBody)
        <!-- Custom Letter Body -->
        <div style="font-size:14px; line-height:1.7; margin-top:8px;">
            {!! $quotation->quotation_letter_body !!}
        </div>
    @else
        <!-- Default Letter Body -->
        <p>WE ARE PLEASED TO LEARN THAT YOU HAVE A REQUIREMENT  
            {{ $quotation->items->count() ? $quotation->items->pluck('product_name')->join(', ') : 'Products/Services' }}
             BASED ON YOUR REQUIREMENT, WE ARE PLEASED TO SUBMIT OUR OFFERAS FOLLOWS.
        </p>

        <div style="font-size:14px; line-height:1.7; margin-top:8px;">
            <p><strong>OUR OFFER CONSISTS OF THE FOLLOWING:</strong></p>

            <ul style="margin-top:10px;">
                <li>TECHNICAL SPECIFICATION</li>
                <li>OPTIONAL ACCESSORIES</li>
                <li>USED ITEM</li>
                <li>STANDARD ACCESSORIES</li>
                <li>TERMS &amp; CONDITIONS</li>
                <li>BANK DETAIL</li>
            </ul>

            <p style="margin-top:10px;">
                WE HOPE YOU SHALL FIND THE OFFER AND TECHNICAL SPECIFICATION
                THERE IN, WELL IN LINE WITH YOUR REQUIREMENT. IF YOU HAVE
                ANY QUERY WITH OFFER, PLEASE DO NOT HESITATE TO CALL OR
                E-MAIL US.
            </p>

            <p style="margin-top:10px;">
                THANK YOU ONCE AGAIN FOR CONSIDERING
                <strong>{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</strong>
                FOR YOUR MACHINE REQUIREMENT. WE ASSURE YOU OF BEST SERVICE
                AND ATTENTION AT ALL TIMES.
            </p>
        </div>
    @endif

    <p style="margin-top:20px;">
        For, <strong>{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</strong>
    </p>

    <p style="margin-top:10px;">
        <!-- <strong></strong><br> -->
        @if($company && $company->phone_number)
            Contact : {{ $company->phone_number }}<br>
        @else
            Contact : +91 9227607851<br>
        @endif

    </p>

<!-- first page over  -->
<!-- ===== PRODUCT PAGES START HERE ===== -->
@foreach($quotation->items as $index => $item)
<div style="page-break-before: always;"></div>

<!-- PRODUCT DETAILS -->
<!-- <div style="margin-top:20px;"> -->
    

    <!-- QUOTATION & CUSTOMER REFERENCE -->
    <table width="100%" cellpadding="4" cellspacing="0" style="font-size:11px; margin-bottom:00px;">
        <tr>
            <td width="70%">
                <!-- <strong>Product M   odel:</strong> {{ $item->product_type ?? 'N/A' }}<br>
                <strong>Quantity:</strong> {{ $item->quantity }}<br> -->
                <h4 style="color:#c00; margin-bottom:10px; font-size:18px; margin-top:0px;">
        <strong>PRODUCT {{ $index + 1 }}: {{ strtoupper($item->product_name) }}</strong>
    </h4>
            </td>
            <td width="30%" align="right" valign="top">
                @if($item->product && $item->product->product_image)
                    @php
                        $imagePath = $item->product->product_image;
                        if (strpos($imagePath, 'storage/') === 0) {
                            $imagePath = substr($imagePath, 8);
                        }
                        $fullImagePath = storage_path('app/public/' . $imagePath);
                    @endphp
                    @if(file_exists($fullImagePath))
                        <img src="{{ $fullImagePath }}" 
                             alt="{{ $item->product_name }}" 
                             style="max-width: 80px; max-height: 80px; border: 1px solid #ddd; padding: 5px;">
                    @else
                        <div style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #999;">
                            No Image
                        </div>
                    @endif
                @else
                    <div style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #999;">
                        No Image
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- PRODUCT DESCRIPTION -->
    @if($item->description)
    <div style="background:#f9f9f9; border:1px solid #ddd; padding:8px; margin-bottom:20px; border-radius:4px;">
        <strong style="font-size:12px;">PRODUCT DESCRIPTION:</strong>
        <p style="font-size:8px; margin:8px 0 0 0; line-height:0.5;">
            {!! nl2br(e($item->description)) !!}
        </p>
    </div>
    @endif

    <!-- PRODUCT SPECIFICATIONS (if available from product master) -->
    @if($item->product)
    <div style="margin-bottom:10px;">
        <!-- <strong style="font-size:11px; color:#333;">TECHNICAL SPECIFICATIONS:</strong> -->
        
        <table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #ddd; font-size:9px; margin-top:2px; margin-bottom:8px; border-collapse: collapse;">
            
            <!-- SECTION 1: BASIC INFO -->
            <tr style="background:#f0f0f0;">
                <td colspan="2" style="border:1px solid #ddd; padding:5px; text-align:left; font-size:10px;"><strong>GENERAL DETAILS</strong></td>
            </tr>
            
            <!-- BASIC PRODUCT INFO -->
            <tr>
                <td width="50%" style="border:1px solid #ddd; padding:5px;"><strong>Product Name:</strong> {{ $item->product->product_name }}</td>
                <td width="50%" style="border:1px solid #ddd; padding:5px;"><strong>Product Model:</strong> {{ $item->product_type }}</td>
            </tr>
            <!-- <tr>
                <td colspan="2" style="border:1px solid #ddd; padding:5px;"><strong>Quantity:</strong> {{ $item->quantity }}</td>
            </tr> -->
         
            <!-- SECTION 2: SPECIFICATIONS -->
            @php
                // Get specs ONLY for this specific product model
                $allSpecs = \App\Models\Product::where('product_master_id', $item->product_id)
                    ->where('product_model', $item->product_type)
                    ->get();
                // Remove duplicates based on spec_name and spec_value
                $uniqueSpecs = $allSpecs->filter(function($s) { return $s->spec_name; })
                    ->unique(function($s) { return $s->spec_name . '|' . $s->spec_value; })
                    ->values();
                $specsCount = $uniqueSpecs->count();
                $midPoint = ceil($specsCount / 2);
                $leftSpecs = $uniqueSpecs->slice(0, $midPoint)->values();
                $rightSpecs = $uniqueSpecs->slice($midPoint)->values();
            @endphp
            
            <tr style="background:#f0f0f0;">
                <td colspan="2" style="border:1px solid #ddd; padding:5px; text-align:left; font-size:10px;"><strong>TECHNICAL SPECIFICATIONS</strong></td>
            </tr>

            @if($specsCount > 0)
                @for($i = 0; $i < $midPoint; $i++)
                <tr>
                    <td width="50%" valign="top" style="border:1px solid #ddd; padding:5px;">
                        @if(isset($leftSpecs[$i]))
                            <strong>{{ $leftSpecs[$i]->spec_name }}:</strong> {{ $leftSpecs[$i]->spec_value }}{{ $leftSpecs[$i]->spec_unit ? ' ' . $leftSpecs[$i]->spec_unit : '' }}
                        @endif
                    </td>
                    <td width="50%" valign="top" style="border:1px solid #ddd; padding:5px;">
                        @if(isset($rightSpecs[$i]))
                            <strong>{{ $rightSpecs[$i]->spec_name }}:</strong> {{ $rightSpecs[$i]->spec_value }}{{ $rightSpecs[$i]->spec_unit ? ' ' . $rightSpecs[$i]->spec_unit : '' }}
                        @endif
                    </td>
                </tr>
                @endfor
            @else
                <tr>
                    <td colspan="2" style="border:1px solid #ddd; text-align:center; padding:5px;"><em>No specifications available</em></td>
                </tr>
            @endif
            
            <!-- SECTION 3: PRICING -->
            <tr style="background:#f0f0f0;">
                <td colspan="2" style="border:1px solid #ddd; padding:5px; text-align:left; font-size:10px;"><strong>PRICING DETAILS</strong></td>
            </tr>
            
            <tr>
                <td style="border:1px solid #ddd; padding:5px;"><strong>Unit Price</strong></td>
                <td style="border:1px solid #ddd; padding:5px;">₹{{ number_format($item->unit_price, 2) }}</td>
            </tr>
            <!-- <tr style="background:#f8f9fa;">
                <td style="border:1px solid #ddd; padding:5px;"><strong>Total Price (Before Tax)</strong></td>
                <td style="border:1px solid #ddd; padding:5px;">₹{{ number_format($item->total_price, 2) }}</td>
            </tr> -->

            <!-- GST CALCULATION -->
            @if($quotation->customer->gst_type == 'instate')
                <tr style="background:#fff9e6;">
                    <td style="border:1px solid #ddd; padding:5px;"><strong>SGST ({{ $quotation->tax_percent / 2 }}%)</strong></td>
                    <td style="border:1px solid #ddd; padding:5px;">+ ₹{{ number_format($item->total_price * (($quotation->tax_percent / 2) / 100), 2) }}</td>
                </tr>
                <tr style="background:#fff9e6;">
                    <td style="border:1px solid #ddd; padding:5px;"><strong>CGST ({{ $quotation->tax_percent / 2 }}%)</strong></td>
                    <td style="border:1px solid #ddd; padding:5px;">+ ₹{{ number_format($item->total_price * (($quotation->tax_percent / 2) / 100), 2) }}</td>
                </tr>
            @elseif($quotation->customer->gst_type == 'outofstate')
                <tr style="background:#e6f2ff;">
                    <td style="border:1px solid #ddd; padding:5px;"><strong>IGST ({{ $quotation->tax_percent }}%)</strong></td>
                    <td style="border:1px solid #ddd; padding:5px;">+ ₹{{ number_format($item->total_price * ($quotation->tax_percent / 100), 2) }}</td>
                </tr>
            @endif

            <tr style="background:#e8f5e9; font-size:11px;">
                <td style="border:1px solid #ddd; padding:6px;"><strong>Final Total (With Tax)</strong></td>
                <td style="border:1px solid #ddd; padding:6px;"><strong>₹{{ number_format($item->total_price * (1 + ($quotation->tax_percent / 100)), 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- PRODUCT NOTE -->
    @if($item->product->note)
    <div style="background:#f5f5f5;  padding:10px; margin-top:15px; margin-bottom:20px;">
        
        <p style="font-size:9px; margin:5px 0 0 0; line-height:1.4; color:#333;">
            {!! nl2br(e($item->product->note)) !!}
        </p>
    </div>
    @endif

    <!-- PRODUCT ACCESSORIES -->
    @php
        $stdAccessories = $item->product->getStandardAccessoriesArray();
        $optAccessories = $item->product->getOptionalAccessoriesArray();
    @endphp
    
    @if(!empty($stdAccessories) || !empty($optAccessories))
    <div style="margin-top:15px; margin-bottom:20px;">
        <strong style="font-size:12px; color:#333;">ACCESSORIES:</strong>
        <table width="100%" cellpadding="6" cellspacing="0" style="border:1px solid #ddd; font-size:9px; margin-top:3px; border-collapse: collapse;">
            <tr style="background:#f0f0f0;">
                <td width="50%" style="border:1px solid #ddd; padding:4px;"><strong>Standard Accessories</strong></td>
                <td width="50%" style="border:1px solid #ddd; padding:4px;"><strong>Optional Accessories</strong></td>
            </tr>
            <tr valign="top">
                <td style="border:1px solid #ddd; padding:4px;">
                    @forelse($stdAccessories as $acc)
                        • {{ $acc }}<br>
                    @empty
                        <em>N/A</em>
                    @endforelse
                </td>
                <td style="border:1px solid #ddd; padding:4px;">
                    @forelse($optAccessories as $acc)
                        • {{ $acc }}<br>
                    @empty
                        <em>N/A</em>
                    @endforelse
                </td>
            </tr>
        </table>
    </div>
    @endif
    @endif

    
</div>

@endforeach
<!-- ===== PRODUCT PAGES END HERE ===== -->

<!-- SUMMARY TABLE (if more than 1 product) -->

<div style="page-break-before: always;"></div>


<div style="margin-top:-5px; margin-bottom:15px; font-size:11px; line-height:1.6;">
    <h3 style="text-align:center; color:#c00; margin-bottom:12px; margin-top:0; font-size:14px;">
        <strong>QUOTATION SUMMARY</strong>
    </h3>

    <table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #333; font-size:11px;">
        <!-- HEADER -->
        <tr style="background:#333; color:#fff;">
            <td width="10%" style="border:1px solid #333; padding:6px; text-align:center;"><strong>No.</strong></td>
            <td width="50%" style="border:1px solid #333; padding:6px;"><strong>Product</strong></td>
            <td @if($quotation->discount_percent && $quotation->discount_percent > 0) width="25%" @else width="40%" @endif style="border:1px solid #333; padding:6px; text-align:right;"><strong>Amount</strong></td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td width="25%" style="border:1px solid #333; padding:6px; text-align:right;"><strong>Discount</strong></td>
            @endif
        </tr>

        @foreach($quotation->items as $index => $item)
        <tr>
            <td style="border:1px solid #ddd; padding:4px; text-align:center;">{{ $index + 1 }}</td>
            <td style="border:1px solid #ddd; padding:4px;">{{ $item->product_name }}</td>
            <td style="border:1px solid #ddd; padding:4px; text-align:right;">
                ₹{{ number_format($item->total_price * (1 + ($quotation->tax_percent / 100)), 2) }}
            </td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td style="border:1px solid #ddd; padding:4px; text-align:right;">
                -₹{{ number_format(($item->total_price * (1 + ($quotation->tax_percent / 100))) * ($quotation->discount_percent / 100), 2) }}
            </td>
            @endif
        </tr>
        @endforeach

        <!-- GRAND TOTAL ROW -->
        <tr style="background:#f0f0f0; font-weight:bold;">
            <td colspan="2" style="border:1px solid #ddd; padding:5px;">GRAND TOTAL(With GST)</td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                ₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return $item->total_price * (1 + ($quotation->tax_percent / 100)); }), 2) }}
            </td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                -₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * (1 + ($quotation->tax_percent / 100))) * ($quotation->discount_percent / 100); }), 2) }}
            </td>
            @endif
        </tr>

        <!-- DISCOUNT ROW (only if discount exists) -->
        @if($quotation->discount_percent && $quotation->discount_percent > 0)
        <tr style="background:#fff3cd; font-weight:bold;">
            <td colspan="2" style="border:1px solid #ddd; padding:5px;">Discount ({{ $quotation->discount_percent }}%)</td>
            <td colspan="2" style="border:1px solid #ddd; padding:5px; text-align:right;">
                -₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * (1 + ($quotation->tax_percent / 100))) * ($quotation->discount_percent / 100); }), 2) }}
            </td>
        </tr>

        <!-- NET TOTAL ROW (After Discount) -->
        <tr style="background:#e8f5e9; font-weight:bold; font-size:11px;">
            <td colspan="2" style="border:1px solid #ddd; padding:5px;">NET TOTAL (After Discount)</td>
            <td colspan="2" style="border:1px solid #ddd; padding:5px; text-align:right;">
                ₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * (1 + ($quotation->tax_percent / 100))) * (1 - $quotation->discount_percent / 100); }), 2) }}
            </td>
        </tr>
        @endif
    </table>
</div>

<!-- TERMS & CONDITIONS SECTION -->
<div style="margin-top:12px; margin-bottom:15px; font-size:11px; line-height:1.5;">
    <h3 style="text-align:center; color:#c00; margin-bottom:6px; margin-top:0; font-size:14px;">
        <strong>TERMS &amp; CONDITIONS</strong>
    </h3>

    @if($quotation->termsConditions->count() > 0)
        <p style="margin-bottom: 4px; margin-top:0; font-size:11px;"><strong>The following terms & conditions apply to this quotation:</strong></p>

        <table width="100%" cellpadding="1" cellspacing="0" style="font-size:11px; margin-bottom:8px;">
            @foreach($quotation->termsConditions as $tc)
            <tr>
                <td width="20%" valign="top" style="padding:1px;"><strong>{{ strtoupper($tc->title) }}</strong></td>
                <td width="3%" valign="top" style="padding:1px;">:</td>
                <td width="77%" valign="top" style="padding:1px;">
                    {{ $tc->description }}
                </td>
            </tr>
            @endforeach
        </table>
    @else
        <p style="text-align: center; color: #666; font-style: italic;">
            No terms and conditions have been selected for this quotation.
        </p>
    @endif
</div>

<!-- ADDITIONAL NOTES SECTION -->
@if($quotation->notes)
<div style="margin-top:12px; margin-bottom:15px; font-size:11px; line-height:1.4;">
    <h3 style="text-align:left; color:#333; margin-bottom:4px; margin-top:0; font-size:12px;">
        <strong>ADDITIONAL NOTES:</strong>
    </h3>
    <div style="padding: 6px; border: 1px dashed #ccc; background-color: #fcfcfc;">
        {!! nl2br(e($quotation->notes)) !!}
    </div>
</div>
@endif

<!--BANK DETAILS SECTION-->
<div style="font-family: DejaVu Sans, sans-serif; font-size:11px; line-height:1.3; margin-top:8px; margin-bottom:5px;">

    <!-- TITLE -->
    <h3 style="text-align:center; color:#c00; margin-bottom:6px; margin-top:0; font-size:14px;">
        <strong>OUR BANK DETAIL</strong>
    </h3>

    <!-- BANK DETAILS TABLE -->
    <table width="100%" cellpadding="3" cellspacing="0" style="border:1px solid #000; font-size:11px; margin-bottom:5px;">
        <tr>
            <td width="30%" style="border:1px solid #000; padding:2px;"><strong>NAME</strong></td>
            <td width="70%" style="border:1px solid #000; padding:2px;">{{ $company ? strtoupper($company->company_name) : 'data not found' }}</td>
        </tr>
        @if($company && $company->bank_name)
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>BANK</strong></td>
            <td style="border:1px solid #000; padding:2px;">{{ $company->bank_name }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>BANK</strong></td>
            <td style="border:1px solid #000; padding:2px;">data not found</td>
        </tr>
        @endif
        @if($company && $company->bank_branch)
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>BRANCH</strong></td>
            <td style="border:1px solid #000; padding:2px;">{{ $company->bank_branch }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>BRANCH</strong></td>
            <td style="border:1px solid #000; padding:2px;">data not fount </td>
        </tr>
        @endif
        @if($company && $company->account_number)
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>ACCOUNT NUMBER</strong></td>
            <td style="border:1px solid #000; padding:2px;">{{ $company->account_number }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>ACCOUNT NUMBER</strong></td>
            <td style="border:1px solid #000; padding:2px;">data not found </td>
        </tr>
        @endif  
        @if($company && $company->ifsc_code)
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>IFSC CODE</strong></td>
            <td style="border:1px solid #000; padding:2px;">{{ $company->ifsc_code }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000; padding:2px;"><strong>IFSC CODE</strong></td>
            <td style="border:1px solid #000; padding:2px;">data not found</td>
        </tr>
        @endif
        @if($company && $company->gst_number)
        <tr>
            <td style="border: 1px solid #000; padding:2px;"><strong>GST TIN </strong></td>
            <td style="border: 1px solid #00; padding:2px;">{{ $company->gst_number }}</td>
        </tr>
        @else
        <tr>
            <td style="border: 1px solid #000; padding:2px;"><strong>GST TIN </strong></td>
            <td style="border: 1px solid #00; padding:2px;">data not found </td>
        </tr>
        @endif
    </table>

    <!-- NOTE -->
    <p style="margin-top:3px; margin-bottom:2px; font-size:11px;">
        We hope you will find above up to satisfaction and await your esteemed order at the earliest.
    </p>

    <p style="margin-top:2px; margin-bottom:1px; font-size:11px;">
        Thanks &amp; Regards,
    </p>

    <p style="margin-top:1px; margin-bottom:2px; font-size:11px;">
        <strong>for, {{ $company ? strtoupper($company->company_name) : 'data not found' }}</strong>
    </p>

    <p style="margin-top:2px; margin-bottom:0; font-size:11px;">
        @if($company && $company->signature_image_path)
            @php
                $sigPath = $company->signature_image_path;
                if (strpos($sigPath, 'storage/') === 0) {
                    $sigPath = public_path($sigPath);
                }
            @endphp
            @if(file_exists($sigPath))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($sigPath)) }}" style="max-height:100px;  margin-bottom: 2px;"><br>
            @endif
        @endif
        <strong>AUTHORIZED SIGNATORY</strong><br>
        Mobile : {{ $company->phone_number }}
    </p>

</div>


<!-- FOOTER -->
<div style="position: fixed;bottom: -25mm;left: 0;right: 0;height: 20mm;border-top: 2px solid #333; padding-top: 6px;">
    <div style="font-size:9pt; text-align:center; line-height:1.4;">
        <strong>{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</strong>,
        @if($company)
            {{ $company->address }}{{ $company->address && ($company->city || $company->state) ? ', ' : '' }}{{ $company->city }}{{ $company->city && $company->state ? ', ' : '' }}{{ $company->state }}{{ $company->postal_code ? '-' . $company->postal_code : '' }}<br>
            @if($company->email || $company->website || $company->phone_number)
                @if($company->email)Email: {{ $company->email }}@endif @if($company->email && $company->website)|@endif @if($company->website)Website: {{ $company->website }}@endif @if(($company->email || $company->website) && $company->phone_number)|@endif @if($company->phone_number) Mobile : {{ $company->phone_number }}@endif
            @endif
        @else
            B/h Glowtech Steel, Gondal Road, Plot No. 103, Kotharia, Rajkot-360004<br>
            Email: alfamachinetools01@gmail.com | Website: www.alfamachinetool.com
        @endif
    </div>
</div>
</body>
</html>
