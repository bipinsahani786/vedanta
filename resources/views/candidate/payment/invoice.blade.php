<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .watermark {
            position: absolute;
            top: 30%;
            left: 20%;
            opacity: 0.08;
            z-index: -1;
            width: 400px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #031b4e;
            padding-bottom: 20px;
        }
        .header img.logo {
            width: 150px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #031b4e;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .details-box {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-box td {
            vertical-align: top;
            width: 50%;
        }
        h3 {
            color: #031b4e;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        p {
            margin: 4px 0;
            line-height: 1.5;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f8f9fa;
            color: #031b4e;
        }
        .invoice-table .amount-col {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    @if(file_exists(public_path('images/logo.png')))
        <img src="{{ public_path('images/logo.png') }}" class="watermark" alt="Vedanta Watermark">
    @endif

    <div class="header">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Vedanta Logo">
        @endif
        <h1>PAYMENT INVOICE</h1>
        <p>Vedanta Placement Agency</p>
    </div>

    <table class="details-box">
        <tr>
            <td>
                <h3>Invoice To:</h3>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                @if($user->profile && $user->profile->vpa_id)
                    <p><strong>Candidate ID:</strong> {{ $user->profile->vpa_id }}</p>
                @endif
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
            </td>
            <td>
                <h3>Payment Details:</h3>
                <p><strong>Transaction ID:</strong> {{ $transaction->transaction_id ?? $transactionId ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ isset($transaction->created_at) ? \Carbon\Carbon::parse($transaction->created_at)->format('d F Y, h:i A') : now()->format('d F Y, h:i A') }}</p>
                <p><strong>Status:</strong> Successful</p>
            </td>
        </tr>
    </table>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Payment Type</th>
                <th class="amount-col">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $description ?? $transaction->formatted_description ?? 'Vedanta Platform Subscription / Service Fee' }}</td>
                <td>{{ $transaction->formatted_description ?? ucwords(str_replace('_', ' ', $transaction->type ?? 'Registration Fee')) }}</td>
                <td class="amount-col">Rs. {{ number_format($amount ?? $transaction->amount ?? 0, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">Total Amount Paid</td>
                <td class="amount-col">Rs. {{ number_format($amount ?? $transaction->amount ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Vedanta Placement Agency</strong> — Empowering Educational Leadership across India</p>
        <p>Career Point Building, 2nd floor, Patna, 800001, Bihar | Email: info@vedantaplacementagency.in | Phone: +91-7070938975</p>
        <p>This is a computer-generated document and does not require a physical signature.</p>
    </div>

</body>
</html>
