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
                    <img src="{{ public_path('storage/' . $company->logo_path) }}" alt="Company Logo"
                         style="width:120px; height:auto; margin-right:15px;">
                @else
                    <img src="{{ public_path('images/logo_al.jpeg') }}" alt="Company "
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
<div style="page-break-before: always;"></div>

<!-- QUOTATION ITEMS TABLE -->
<div style="margin-top:20px;">
    <table width="100%" cellpadding="4" cellspacing="0" border="1" style="margin-bottom:15px;">
        <!-- TABLE HEADER -->
        <tr style="background:#f0f0f0;">
            <td width="5%" align="center" border="1"><strong>No.</strong></td>
            <td width="45%" align="left" border="1"><strong>Description</strong></td>
            <td width="15%" align="right" border="1"><strong>Rate</strong></td>
            <td width="10%" align="center" border="1"><strong>Unit</strong></td>
            <td width="25%" align="right" border="1"><strong>Amount</strong></td>
        </tr>

        <!-- TABLE ITEMS -->
        @foreach($quotation->items as $index => $item)
        <tr>
            <td align="center" border="1">{{ $index + 1 }}</td>
            <td align="left" border="1">
                <strong>{{ $item->product_name }}</strong><br>
                <strong>Model:</strong> {{ $item->product->product_model ?? 'N/A' }}<br>
                @php
                    // Get all specs for this product model
                    $allSpecs = \App\Models\Product::where('product_master_id', $item->product->product_master_id)
                        ->where('product_model', $item->product->product_model)
                        ->where('price', $item->product->price)
                        ->get();
                @endphp
                @if($allSpecs->count() > 0)
                    <strong>Specifications:</strong><br>
                    @foreach($allSpecs as $spec)
                        @if($spec->spec_name)
                        • {{ $spec->spec_name }}: {{ $spec->spec_value }}{{ $spec->spec_unit ? ' ' . $spec->spec_unit : '' }}<br>
                        @endif
                    @endforeach
                @endif
                @if($item->description)
                    <strong>Details:</strong> {{ substr($item->description, 0, 100) }}{{ strlen($item->description) > 100 ? '...' : '' }}
                @endif
            </td>
            <td align="right" border="1">₹{{ number_format($item->unit_price, 2) }}</td>
            <td align="center" border="1">{{ $item->quantity }}</td>
            <td align="right" border="1">₹{{ number_format($item->total_price, 2) }}</td>
        </tr>
        @endforeach

        <!-- SUBTOTAL ROW -->
        <tr style="background:#f0f0f0;">
            <td colspan="4" align="right" border="1"><strong>Sub Total</strong></td>
            <td align="right" border="1"><strong>₹{{ number_format($quotation->subtotal, 2) }}</strong></td>
        </tr>

        <!-- TAX ROW -->
        @if($quotation->tax_percent > 0)
        <tr style="background:#f0f0f0;">
            <td colspan="4" align="right" border="1">
                <strong>
                    @if($quotation->customer->gst_type == 'instate')
                        (CGST+SGST) 9%+9% = {{ $quotation->tax_percent }}%
                    @else
                        (IGST) {{ $quotation->tax_percent }}%
                    @endif
                </strong>
            </td>
            <td align="right" border="1"><strong>₹{{ number_format($quotation->tax_amount, 2) }}</strong></td>
        </tr>
        @endif

        <!-- DISCOUNT ROW -->
        @if($quotation->discount_amount > 0)
        <tr style="background:#f0f0f0;">
            <td colspan="4" align="right" border="1"><strong>Discount</strong></td>
            <td align="right" border="1"><strong>-₹{{ number_format($quotation->discount_amount, 2) }}</strong></td>
        </tr>
        @endif

        <!-- TOTAL ROW -->
        <tr style="background:#333; color:#fff;">
            <td colspan="4" align="right" border="1"><strong>TOTAL</strong></td>
            <td align="right" border="1"><strong>₹{{ number_format($quotation->total_amount, 2) }}</strong></td>
        </tr>
    </table>
</div>

<!-- QUOTATION SUMMARY TABLE -->
<!-- QUOTATION SUMMARY -->
<div style="margin-top:30px;">
    <div style="text-align:center; font-weight:bold; color:#c00; margin-bottom:10px;">
        QUOTATION SUMMARY
    </div>

    <table width="100%" cellpadding="6" cellspacing="0"
        style="border:1px solid #ddd; font-size:11px; border-collapse:collapse;">

        <!-- Header -->
        <tr style="background:#f0f0f0;">
            <td style="border:1px solid #ddd; font-weight:bold;">Product Name</td>
            <td style="border:1px solid #ddd; font-weight:bold; text-align:right;">
                Amount (With Tax)
            </td>
            <td style="border:1px solid #ddd; font-weight:bold; text-align:center;">
                Discount
            </td>
        </tr>

        <!-- Product Rows -->
        @foreach($quotation->items as $item)
        <tr>
            <td style="border:1px solid #ddd;">
                {{ $item->product_name }}
            </td>
            <td style="border:1px solid #ddd; text-align:right;">
                ₹{{ number_format($item->final_total, 2) }}
            </td>
            <td style="border:1px solid #ddd; text-align:center;">
                {{ $item->discount ?? '-' }}
            </td>
        </tr>
        @endforeach

        <!-- Grand Total -->
        <tr style="background:#f7f7f7; font-weight:bold;">
            <td style="border:1px solid #ddd;">GRAND TOTAL</td>
            <td style="border:1px solid #ddd; text-align:right;">
                ₹{{ number_format($quotation->grand_total, 2) }}
            </td>
            <td style="border:1px solid #ddd; text-align:center;">-</td>
        </tr>

    </table>
</div>

<!-- ===== SUMMARY TABLE END ===== -->

<div style="position: fixed;bottom: -15mm;left: 0;right: 0;height: 20mm;border-top: 2px solid #333; padding-top: 6px;">
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


<!-- TERMS & CONDITIONS -->

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


<div style="position: fixed;bottom: -15mm;left: 0;right: 0;height: 20mm;border-top: 2px solid #333; padding-top: 6px;">
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
            <td width="70%" style="border:1px solid #000;">{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</td>
        </tr>
        @if($company && $company->bank_name)
        <tr>
            <td style="border:1px solid #000;"><strong>BANK</strong></td>
            <td style="border:1px solid #000;">{{ $company->bank_name }}</td>
        </tr>
        @else
        <tr>
            <td style="border:1px solid #000;"><strong>BANK</strong></td>
            <td style="border:1px solid #000;">KOTAK BANK</td>
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
            <td style="border:1px solid #000;">Kalawad Road Branch, RAJKOT</td>
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
            <td style="border:1px solid #000;">4712622406</td>
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
            <td style="border:1px solid #000;">KKBK0002794</td>
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
            <td style="border: 1px solid #00">24AADFA5082H1ZP</td>
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
        <strong>for ,{{ $company ? strtoupper($company->company_name) : 'ALFA MACHINE TOOLS' }}</strong>
    </p>

    <p style="margin-top:15px;">
        <strong>AUTHORIZED SIGNATORY</strong><br>
        Mobile : +91 XXXXXXXXXX
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
                            <img src="/storage/{{ $company->web_logo_path }}"
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
                            <img src="/storage/{{ $company->phone_icon_path }}"
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
                            <img src="/storage/{{ $company->mail_icon_path }}"
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
<div style="position: fixed;bottom: -15mm;left: 0;right: 0;height: 20mm;border-top: 2px solid #333; padding-top: 6px;">
    <div style="font-size:9pt; text-align:center; line-height:1.4;">
        <strong>ALFA MACHINE TOOLS</strong>,  
        B/h Glowtech Steel, Gondal Road, Plot No. 103, Kotharia, Rajkot-360004<br>
        Email: alfamachinetools01@gmail.com | Website: www.alfamachinetool.com
    </div>
</div>
</body>
</html>
