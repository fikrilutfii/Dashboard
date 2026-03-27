<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 0; padding: 0; color: #000; }
        
        @page {
            size: 21.3cm 15.5cm; /* Landscape: Width Height */
            margin: 0.5cm 0.5cm 0.2cm 0.5cm; /* Top Right Bottom Left */
        }

        .container { 
            width: 100%; 
            position: relative; 
        }

        /* Top Header: Logo (Left) vs Date/Customer (Right) */
        .top-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            margin-bottom: 5px; 
        }

        .header-left-section { 
            width: 50%;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-img { height: 45px; width: auto; }
        .company-name { 
            font-size: 20pt; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            color: #333;
            line-height: 1;
        }

        .header-right-section { 
            width: 40%;
            text-align: right;
            font-size: 9pt;
        }
        .header-right-section .date { margin-bottom: 2px; }
        .customer-box { margin-top: 2px; line-height: 1.2; }

        /* Sub Header: Faktur No (Left) vs OP No (Right) */
        .sub-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end; /* Align bottom to match text baselines if needed */
            margin-bottom: 5px;
            margin-top: 10px;
            padding-bottom: 2px;
        }
        .faktur-no-section {
            font-size: 11pt;
            font-weight: bold;
        }
        .faktur-label { letter-spacing: 1px; }
        .faktur-value { color: red; margin-left: 5px; }

        .op-no-section {
            font-size: 10pt;
            font-weight: normal;
        }

        /* Table Structure */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 2px; 
            border: 1px solid #000; 
        }
        
        th { 
            border: 1px solid #000; 
            padding: 2px; 
            text-align: center; 
            font-weight: bold; 
            font-size: 9pt;
        }
        
        td { 
            border-left: 1px solid #000; 
            border-right: 1px solid #000; 
            padding: 2px 5px; 
            vertical-align: top; 
            font-size: 10pt;
            font-family: "Courier New", Courier, monospace; 
        }
        
        /* Column Widths */
        .col-no { width: 30px; text-align: center; }
        .col-item { } 
        .col-qty { width: 60px; text-align: center; }
        .col-unit { width: 40px; text-align: center; }
        .col-price { width: 90px; text-align: right; }
        .col-total { width: 100px; text-align: right; }

        tr.item-row { height: 16px; } 
        tr.item-row td { border-bottom: none; border-top: none; }

        /* Footer */
        .footer { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            margin-top: 10px; /* Space between table and signature */
            font-size: 9pt;
        }
        
        .footer-left { width: 40%; }
        .footer-right { width: 30%; text-align: center; }

        .signature-box { 
            text-align: center; 
            width: 120px; 
        }
        /* Increased signature space */
        .sign-title { margin-bottom: 70px; } 
        .sign-line { border-bottom: 1px solid #000; width: 100%; display: block; }

        .attention-box { 
            margin-top: 5px; 
            font-size: 8pt; 
            font-family: Arial, sans-serif;
            text-align: left;
        }
        .attention-header { font-weight: bold; text-decoration: underline; margin-bottom: 1px; }
        .attention-body { line-height: 1.1; font-style: italic; }

        tfoot td {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-family: Arial, sans-serif;
            padding: 2px 5px;
            font-size: 9pt;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
            .invoice-number { color: red !important; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        
        <!-- Top Header: Logo Left, Date/Customer Right -->
        <div class="top-header">
            <div class="header-left-section">
                <!-- Logo & Company Name -->
                <img src="{{ asset('images/logo.jpg') }}" class="logo-img" alt="logo">
                <div class="company-name">ABADI SENTOSA</div>
            </div>
            
            <div class="header-right-section">
                <div class="date">Bandung, {{ $invoice->invoice_date->format('d F Y') }}</div>
                <div class="customer-box">
                    <span>Kepada Yth.</span><br>
                    <strong>{{ $invoice->customer->name }}</strong><br>
                    {{ $invoice->customer->address ?? '' }}
                </div>
            </div>
        </div>

        <!-- Sub Header: Faktur No Left, OP No Right -->
        <div class="sub-header">
            <div class="faktur-no-section">
                <span class="faktur-label">FAKTUR NO. :</span>
                <span class="faktur-value invoice-number">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="op-no-section">
                <!-- Corrected from DP No to OP No -->
                OP No. ________________
            </div>
        </div>

        <!-- Table Content -->
        <!-- Fixed height container expanded to 8.5cm to fill space -->
        <div style="min-height: 7.5cm; display: flex; flex-direction: column; justify-content: space-between;"> 
            <table>
                <thead>
                    <tr>
                        <th class="col-no">No.</th>
                        <th class="col-item">Nama Barang</th>
                        <th class="col-qty">Banyaknya</th>
                        <th class="col-unit">Satuan</th>
                        <th class="col-price">Harga</th>
                        <th class="col-total">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Increased limit to 14 to fill the table downwards -->
                    @php $limit = 10; $count = count($invoice->items); @endphp
                    @foreach ($invoice->items as $index => $item)
                        <tr class="item-row">
                            <td class="col-no">{{ $index + 1 }}</td>
                            <td class="col-item">{{ $item->item_name }} <br> <span style="font-size: 8pt;">{{ $item->specification }}</span></td>
                            <td class="col-qty">{{ $item->quantity }}</td>
                            <td class="col-unit">Pcs</td>
                            <td class="col-price">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="col-total">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    
                    @for($i = $count; $i < $limit; $i++)
                        <tr class="item-row">
                            <td class="col-no">&nbsp;</td>
                            <td class="col-item">&nbsp;</td>
                            <td class="col-qty">&nbsp;</td>
                            <td class="col-unit">&nbsp;</td>
                            <td class="col-price">&nbsp;</td>
                            <td class="col-total">&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; border-right: 1px solid #000; padding-right: 10px;">Jumlah</td>
                        <td class="col-total" style="text-align: right;">{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <!-- Tanda Terima -->
                <div class="signature-box" style="margin-bottom: 5px;">
                    <div class="sign-title">Tanda terima</div>
                    <div class="sign-line"></div>
                </div>
                
                <!-- Perhatian (Below Tanda Terima) -->
                <div class="attention-box">
                    <div class="attention-header">PERHATIAN :</div>
                    <div class="attention-body">
                        Barang-barang tersebut di atas isi cetakan diluar tanggung jawab kami.<br>
                        Barang-barang yang sudah di cetak tidak dapat dikembalikan.
                    </div>
                </div>
            </div>
            
            <div class="footer-right">
                <!-- Hormat Kami -->
                <div class="signature-box" style="margin-left: auto; margin-right: auto;">
                    <div class="sign-title">Hormat Kami,</div>
                    <div class="sign-line"></div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
