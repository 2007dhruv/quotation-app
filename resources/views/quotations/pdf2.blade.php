@php
    $company = \App\Models\Company::getPrimary();
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
                @if($company && $company->logo_path)
                    <img src="{{ $company->logo_path }}" alt="Company Logo"
                         style="width:120px; height:auto; margin-right:15px;">
                @else
                    <img src="/images/logo_alfa.jpeg" alt=" Logo"
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
                    @if($company && ($company->city || $company->state))
                        {{ $company->address ?? '' }}{{ ($company->address && ($company->city || $company->state)) ? ', ' : '' }}{{ $company->city }}{{ ($company->city && $company->state) ? ', ' : '' }}{{ $company->state }}
                    @else
                        Rajkot Industrial Area, Gujarat, India
                    @endif
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
            @if($quotation->customer->mobile)<strong>Mobile No:</strong> {{ $quotation->customer->mobile }}<br>@endif
            <!-- @if($quotation->customer->email)<strong>Email:</strong> {{ $quotation->customer->email }}<br>@endif
            @if($quotation->customer->gst_no)<strong>GSTIN:</strong> {{ $quotation->customer->gst_no }}@endif -->
        </td>

        <!-- QUOTATION INFO -->
        <td width="35%" valign="top" align="right">
            <table width="100%" cellpadding="3" cellspacing="0">
                <tr>
                    <td align="right"><strong>Quote No. :</strong></td>
                    <td align="left">{{ $quotation->quotation_number }}</td>
                </tr>
                <tr>
                    <td align="right"><strong>Date :</strong></td>
                    <td align="left">{{ $quotation->quotation_date->format('d-m-Y') }}</td>
                </tr>
                @if($quotation->valid_until)
                <tr>
                    <td align="right"><strong>Valid Until :</strong></td>
                    <td align="left">{{ $quotation->valid_until->format('d-m-Y') }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
<br>
    <h3>&ensp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SUBJECT : QUOTATION FOR 
        {{ $quotation->items->count() > 0 ? $quotation->items->first()->product_name : 'Products/Services' }}
        @if($quotation->items->count() > 1)
            &amp; {{ $quotation->items->count() - 1 }} more
        @endif
    </strong></h3>  
    <h4 style="font: size 14px;">DEAR SIR ,</h4>
      <p>WE ARE PLEASED TO LEARN THAT YOU HAVE A REQUIREMENT  
          {{ $quotation->items->count() ? $quotation->items->pluck('product_name')->join(', ') : 'Products/Services' }}
           BASED ON YOUR REQUIREMENT, WE ARE PLEASED TO SUBMIT OUR OFFERAS FOLLOWS.
      </p>
      <div style="font-size:14px; line-height:1.7; margin-top:8px;">

    <p><strong>OUR OFFER CONSISTS OF THE FOLLOWING:</strong></p>

    <ul style=" margin-top:10px;">
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
</div>

<!-- first page over  -->
<!-- ===== PRODUCT PAGES START HERE ===== -->
@foreach($quotation->items as $index => $item)
<div style="page-break-before: always;"></div>

<!-- PRODUCT DETAILS -->
<!-- <div style="margin-top:20px;"> -->
    <h4 style="color:#c00; margin-bottom:10px; font-size:18px; margin-top:0px;">
        <strong>PRODUCT {{ $index + 1 }}: {{ strtoupper($item->product_name) }}</strong>
    </h4>

    <!-- QUOTATION & CUSTOMER REFERENCE -->
    <table width="100%" cellpadding="4" cellspacing="0" style="font-size:11px; margin-bottom:00px;">
        <tr>
            <td width="50%">
                <strong>Product Type:</strong> {{ $item->product_type ?? 'N/A' }}<br>
                <strong>Quantity:</strong> {{ $item->quantity }}<br>
                <strong>Unit Price:</strong> ₹{{ number_format($item->unit_price, 2) }}
            </td>
            <td width="50%" align="right" valign="top">
                @if($item->product && $item->product->product_image)
                    <img src="{{ public_path($item->product->product_image) }}" 
                         alt="{{ $item->product_name }}" 
                         style="max-width: 80px; max-height: 80px; border: 1px solid #ddd; padding: 5px;">
                @else
                    <div style="width: 150px; height: 150px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">
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
    <div style="margin-bottom:20px;">
        <strong style="font-size:12px; color:#333;">TECHNICAL SPECIFICATIONS:</strong>
        <table width="100%" cellpadding="6" cellspacing="0" style="border:1px solid #ddd; font-size:8px; margin-top:3px;">
            <tr style="background:#f0f0f0;">
                <td width="40%" style="border:1px solid #ddd;"><strong>Specification</strong></td>
                <td width="60%" style="border:1px solid #ddd;"><strong>Details</strong></td>
            </tr>
            
            <!-- BASIC PRODUCT INFO -->
            <tr>
                <td style="border:1px solid #ddd;">Product Name</td>
                <td style="border:1px solid #ddd;">{{ $item->product_name }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #ddd;">Product Model</td>
                <td style="border:1px solid #ddd;">{{ $item->product_type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #ddd;">Quantity</td>
                <td style="border:1px solid #ddd;">{{ $item->quantity }}</td>
            </tr>
            
            <!-- PRODUCT SPECIFICATIONS FROM DATABASE -->
            @forelse($item->product->specifications as $spec)
            <tr>
                <td style="border:1px solid #ddd;">{{ $spec->spec_name }}</td>
                <td style="border:1px solid #ddd;">{{ $spec->spec_value }}{{ $spec->spec_unit ? ' ' . $spec->spec_unit : '' }}</td>
            </tr>
            @empty
            <tr>
                <td style="border:1px solid #ddd;" colspan="2"><em>No additional specifications available</em></td>
            </tr>
            @endforelse
            
            <!-- PRICING INFO -->
            <tr>
                <td style="border:1px solid #ddd;">Unit Price</td>
                <td style="border:1px solid #ddd;">₹{{ number_format($item->unit_price, 2) }}</td>
            </tr>
            <tr style="background:#f0f0f0; font-weight:bold;">
                <td style="border:1px solid #ddd;">Total Price (Before Tax)</td>
                <td style="border:1px solid #ddd;">₹{{ number_format($item->total_price, 2) }}</td>
            </tr>

            <!-- GST CALCULATION -->
            @if($quotation->customer->gst_type == 'instate')
                <tr style="background:#fff9e6;">
                    <td style="border:1px solid #ddd;">SGST (9%)</td>
                    <td style="border:1px solid #ddd;">+ ₹{{ number_format($item->total_price * 0.09, 2) }}</td>
                </tr>
                <tr style="background:#fff9e6;">
                    <td style="border:1px solid #ddd;">CGST (9%)</td>
                    <td style="border:1px solid #ddd;">+ ₹{{ number_format($item->total_price * 0.09, 2) }}</td>
                </tr>
                <!-- <tr style="background:#fff9e6; font-weight:bold;">
                    <td style="border:1px solid #ddd;">Total Tax (18%)</td>
                    <td style="border:1px solid #ddd;">₹{{ number_format($item->total_price * 0.18, 2) }}</td>
                </tr> -->
            @else
                <tr style="background:#e6f2ff;">
                    <td style="border:1px solid #ddd;">IGST (18%)</td>
                    <td style="border:1px solid #ddd;">₹{{ number_format($item->total_price * 0.18, 2) }}</td>
                </tr>
            @endif

            <tr style="background:#e8f5e9; font-weight:bold; font-size:9px;">
                <td style="border:1px solid #ddd;">Final Total (With Tax)</td>
                <td style="border:1px solid #ddd;">₹{{ number_format($item->total_price * 1.18, 2) }}</td>
            </tr>
        </table>
    </div>
    @endif

    
</div>

@endforeach
<!-- ===== PRODUCT PAGES END HERE ===== -->

<!-- SUMMARY TABLE (if more than 1 product) -->
@if($quotation->items->count() > 1)
<div style="page-break-before: always;"></div>

<div style="margin-top:20px; font-size:12px; line-height:1.6;">
    <h3 style="text-align:center; color:#c00; margin-bottom:15px;">
        <strong>QUOTATION SUMMARY</strong>
    </h3>

    <table width="100%" cellpadding="8" cellspacing="0" style="border:1px solid #333; font-size:11px;">
        <!-- HEADER -->
        <tr style="background:#333; color:#fff;">
            <td width="10%" style="border:1px solid #333; padding:10px; text-align:center;"><strong>No.</strong></td>
            <td width="50%" style="border:1px solid #333; padding:10px;"><strong>Product</strong></td>
            <td @if($quotation->discount_percent && $quotation->discount_percent > 0) width="25%" @else width="40%" @endif style="border:1px solid #333; padding:10px; text-align:right;"><strong>Amount</strong></td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td width="25%" style="border:1px solid #333; padding:10px; text-align:right;"><strong>Discount</strong></td>
            @endif
        </tr>

        <!-- PRODUCT ROWS -->
        @foreach($quotation->items as $index => $item)
        <tr>
            <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $index + 1 }}</td>
            <td style="border:1px solid #ddd; padding:8px;">{{ $item->product_name }}</td>
            <td style="border:1px solid #ddd; padding:8px; text-align:right;">
                ₹{{ number_format($item->total_price * 1.18, 2) }}
            </td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td style="border:1px solid #ddd; padding:8px; text-align:right;">
                -₹{{ number_format(($item->total_price * 1.18) * ($quotation->discount_percent / 100), 2) }}
            </td>
            @endif
        </tr>
        @endforeach

        <!-- GRAND TOTAL ROW -->
        <tr style="background:#f0f0f0; font-weight:bold;">
            <td colspan="2" style="border:1px solid #ddd; padding:10px;">GRAND TOTAL</td>
            <td style="border:1px solid #ddd; padding:10px; text-align:right;">
                ₹{{ number_format($quotation->items->sum(function($item) { return $item->total_price * 1.18; }), 2) }}
            </td>
            @if($quotation->discount_percent && $quotation->discount_percent > 0)
            <td style="border:1px solid #ddd; padding:10px; text-align:right;">
                -₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * 1.18) * ($quotation->discount_percent / 100); }), 2) }}
            </td>
            @endif
        </tr>

        <!-- DISCOUNT ROW (only if discount exists) -->
        @if($quotation->discount_percent && $quotation->discount_percent > 0)
        <tr style="background:#fff3cd; font-weight:bold;">
            <td colspan="2" style="border:1px solid #ddd; padding:10px;">Discount ({{ $quotation->discount_percent }}%)</td>
            <td colspan="2" style="border:1px solid #ddd; padding:10px; text-align:right;">
                -₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * 1.18) * ($quotation->discount_percent / 100); }), 2) }}
            </td>
        </tr>

        <!-- NET TOTAL ROW (After Discount) -->
        <tr style="background:#e8f5e9; font-weight:bold; font-size:12px;">
            <td colspan="2" style="border:1px solid #ddd; padding:10px;">NET TOTAL (After Discount)</td>
            <td colspan="2" style="border:1px solid #ddd; padding:10px; text-align:right;">
                ₹{{ number_format($quotation->items->sum(function($item) use($quotation) { return ($item->total_price * 1.18) * (1 - $quotation->discount_percent / 100); }), 2) }}
            </td>
        </tr>
        @endif
    </table>
</div>
@endif
<!-- ===== SUMMARY TABLE END ===== -->

<div style="page-break-before: always;"></div>

<!-- TERMS & CONDITIONS PAGE -->
<div style="margin-top:-20px; font-size:12px; line-height:1.6;">

    <h3 style="text-align:center; color:#c00; margin-bottom:15px;">
        <strong>TERMS &amp; CONDITIONS</strong>
    </h3>

    <table width="100%" cellpadding="6" cellspacing="0">
        @forelse($termsConditions as $tc)
        <tr>
            <td width="22%" valign="top"><strong>{{ strtoupper($tc->title) }}</strong></td>
            <td width="3%" valign="top">:</td>
            <td width="75%" valign="top">
                {{ $tc->description }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" valign="top" style="text-align:center;">
                <em>No terms and conditions configured. Please add them from the admin panel.</em>
            </td>
        </tr>
        @endforelse
    </table>

</div>



<div style="page-break-before: always;"></div>

<!--BANK DETAILS PAGE-->
<!-- Body -->
<div style="font-family: DejaVu Sans, sans-serif; font-size:12px; line-height:1.6;">

    <!-- TITLE -->
    <h2 style="text-align:center; margin-bottom:15px;">
        <strong>OUR BANK DETAIL</strong>
    </h2>

    <!-- BANK DETAILS TABLE -->
    <table width="100%" cellpadding="6" cellspacing="0" style="border:1px solid #000; font-size:12px;">
        <tr>
            <td width="30%" style="border:1px solid #000;"><strong>NAME</strong></td>
            <td width="70%" style="border:1px solid #000;">{{ $company ? strtoupper($company->company_name) : 'data not found' }}</td>
        </tr>
        @if($company && $company->bank_name)
        <tr>
            <td style="border:1px solid #000;"><strong>BANK</strong></td>
            <td style="border:1px solid #000;">{{ $company->bank_name }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000;"><strong>BANK</strong></td>
            <td style="border:1px solid #000;">data not found</td>
        </tr>
        @endif
        @if($company && $company->bank_branch)
        <tr>
            <td style="border:1px solid #000;"><strong>BRANCH</strong></td>
            <td style="border:1px solid #000;">{{ $company->bank_branch }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000;"><strong>BRANCH</strong></td>
            <td style="border:1px solid #000;">data not fount </td>
        </tr>
        @endif
        @if($company && $company->account_number)
        <tr>
            <td style="border:1px solid #000;"><strong>ACCOUNT NUMBER</strong></td>
            <td style="border:1px solid #000;">{{ $company->account_number }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000;"><strong>ACCOUNT NUMBER</strong></td>
            <td style="border:1px solid #000;">data not found </td>
        </tr>
        @endif
        @if($company && $company->ifsc_code)
        <tr>
            <td style="border:1px solid #000;"><strong>IFSC CODE</strong></td>
            <td style="border:1px solid #000;">{{ $company->ifsc_code }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000;"><strong>IFSC CODE</strong></td>
            <td style="border:1px solid #000;">data not found</td>
        </tr>
        @endif
        @if($company && $company->gst_number)
        <tr>
            <td style="border: 1px solid #000;"><strong>GST TIN </strong></td>
            <td style="border: 1px solid #00">{{ $company->gst_number }}</td>
        </tr>
        @else
        <tr>
            <td style="border: 1px solid #000;"><strong>GST TIN </strong></td>
            <td style="border: 1px solid #00">data not found </td>
        </tr>
        @endif
    </table>

    <!-- NOTE -->
    <p style="margin-top:15px;">
        We hope you will find above up to satisfaction and await your esteemed
        order at the earliest.
    </p>

    <p style="margin-top:10px;">
        Thanks &amp; Regards,
    </p>

    <p style="margin-top:5px;">
        <strong>for ,{{ $company ? strtoupper($company->company_name) : 'data not found' }}</strong>
    </p>

    <p style="margin-top:15px;">
        <strong>AUTHORIZED SIGNATORY</strong><br>
        Mobile : {{ $company->phone_number }}
    </p>


  <table width="100%" cellpadding="5" cellspacing="0" style="font-size:14px;">
    <tr>
        <!-- LEFT: CONTACT DETAILS -->
        <td width="60%" valign="top">
            <p><strong>Please Visit Us :</strong></p>

            <table cellpadding="4" cellspacing="0">
                <tr>
                    <td width="20" valign="middle">
                        @if($company && $company->web_logo_path)
                            <img src="{{ $company->web_logo_path }}"
                                 style="width:14px; height:auto;">
                        @else
                            <img src="/images/weblogo.png"
                                 style="width:14px; height:auto;">
                        @endif
                    </td>
                    <td valign="middle">
                        {{ $company && $company->website ? $company->website : 'www.alfamachinetool.com' }}
                    </td>
                </tr>

                <tr>
                    <td width="20" valign="middle">
                        @if($company && $company->phone_icon_path)
                            <img src="{{ $company->phone_icon_path }}"
                                 style="width:14px; height:auto;">
                        @else
                            <img src="/images/phone.png"
                                 style="width:14px; height:auto;">
                        @endif
                    </td>
                    <td valign="middle">
                        {{ $company && $company->phone_number ? $company->phone_number : '+91 9227607851' }}
                    </td>
                </tr>

                <tr>
                    <td width="20" valign="middle">
                        @if($company && $company->mail_icon_path)
                            <img src="{{ $company->mail_icon_path }}"
                                 style="width:14px; height:auto;">
                        @else
                            <img src="/images/mail.png"
                                 style="width:14px; height:auto;">
                        @endif
                    </td>
                    <td valign="middle">
                        {{ $company && $company->email ? $company->email : 'info@alfamachinetools.com' }}
                    </td>
                </tr>
            </table>
        </td>

        <!-- RIGHT: QR CODE -->
        <td width="40%" align="right" valign="top">
            @if($company && $company->qr_code_path)
                <img src="/storage/{{ $company->qr_code_path }}"
                     alt="QR Code"
                     style="width:120px; height:auto;">
            @else
                <img src="/images/qr-code.png"
                     alt="QR Code"
                     style="width:120px; height:auto;">
            @endif
        </td>
    </tr>
</table>

</div>


<!-- FOOTER -->
<div style="position: fixed;bottom: -25mm;left: 0;right: 0;height: 20mm;border-top: 2px solid #333; padding-top: 6px;">
    <div style="font-size:9pt; text-align:center; line-height:1.4;">
        <strong>{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</strong>,
        @if($company)
            {{ $company->address }}{{ $company->address && ($company->city || $company->state) ? ', ' : '' }}{{ $company->city }}{{ $company->city && $company->state ? ', ' : '' }}{{ $company->state }}{{ $company->postal_code ? '-' . $company->postal_code : '' }}<br>
            @if($company->email || $company->website)
                @if($company->email)Email: {{ $company->email }}@endif @if($company->email && $company->website)|@endif @if($company->website)Website: {{ $company->website }}@endif
            @endif
        @else
            B/h Glowtech Steel, Gondal Road, Plot No. 103, Kotharia, Rajkot-360004<br>
            Email: alfamachinetools01@gmail.com | Website: www.alfamachinetool.com
        @endif
    </div>
</div>
</body>
</html>
