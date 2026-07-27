<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Charge Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 25px;
            color: #1e293b;
            font-size: 13px;
        }
        .watermark {
            position: absolute;
            top: 25%;
            left: 20%;
            opacity: 0.05;
            z-index: -1;
            width: 420px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #031b4e;
            padding-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            width: 140px;
        }
        .header-title {
            text-align: right;
        }
        .header-title h1 {
            margin: 0;
            color: #031b4e;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-title p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 12px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .badge-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .badge-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .details-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-table td {
            vertical-align: top;
            width: 50%;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
        }
        h3 {
            color: #031b4e;
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        p {
            margin: 5px 0;
            line-height: 1.5;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #031b4e;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .amount-col {
            text-align: right !important;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f1f5f9;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    @if(file_exists(public_path('images/logo.png')))
        <img src="{{ public_path('images/logo.png') }}" class="watermark" alt="Watermark">
    @endif

    <div class="header">
        <table>
            <tr>
                <td>
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Vedanta Logo">
                    @else
                        <h2 style="color: #031b4e; margin:0;">Vedanta Placement</h2>
                    @endif
                </td>
                <td class="header-title">
                    <h1>Service Charge Invoice</h1>
                    <p>Invoice #INV-SC-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <span class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : ($invoice->status === 'overdue' ? 'badge-overdue' : 'badge-pending') }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <tr>
            <td style="padding-right: 10px;">
                <div class="card">
                    <h3>Billed To (Candidate):</h3>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                </div>
            </td>
            <td style="padding-left: 10px;">
                <div class="card">
                    <h3>Invoice Summary:</h3>
                    <p><strong>Issued Date:</strong> {{ $invoice->created_at ? $invoice->created_at->format('d M, Y') : 'N/A' }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') : 'N/A' }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($invoice->status) }}</p>
                    @if($invoice->status === 'paid' && $invoice->payment_date)
                        <p><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($invoice->payment_date)->format('d M, Y, h:i A') }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Job Post</th>
                <th class="amount-col">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Placement Service Charge</strong><br>
                    <small style="color: #64748b;">Service charge for placement assistance and recruitment processing.</small>
                </td>
                <td>
                    {{ $invoice->jobApplication->jobPost->title ?? 'Educational Institution Placement' }}
                </td>
                <td class="amount-col">₹{{ number_format($invoice->amount, 2) }}</td>
            </tr>
            @if(($invoice->late_fee ?? 0) > 0)
            <tr>
                <td>
                    <strong>Overdue Late Fee</strong><br>
                    <small style="color: #ef4444;">Accrued late payment fee charges.</small>
                </td>
                <td>-</td>
                <td class="amount-col" style="color: #dc2626;">₹{{ number_format($invoice->late_fee, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">Total Amount {{ $invoice->status === 'paid' ? 'Paid' : 'Due' }}</td>
                <td class="amount-col" style="color: #031b4e;">₹{{ number_format($invoice->amount + ($invoice->late_fee ?? 0), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Vedanta Placement Agency</strong> — Empowering Educational Leadership across India</p>
        <p>This is a computer-generated document. No signature required.</p>
    </div>

</body>
</html>
