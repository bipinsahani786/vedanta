<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; margin: 0; padding: 20px; font-size: 13px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { width: 100%; text-align: center; margin-bottom: 40px; border-bottom: 2px solid #031b4e; padding-bottom: 20px; }
        .header img { max-height: 60px; }
        .invoice-title { font-size: 28px; color: #031b4e; font-weight: bold; margin-top: 10px; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 8px; vertical-align: top; }
        .details-table { margin-bottom: 40px; }
        .details-table td.title { font-weight: bold; color: #555; }
        .item-table tr.heading td { background: #f8fafc; border-bottom: 2px solid #ddd; font-weight: bold; }
        .item-table tr.item td { border-bottom: 1px solid #eee; }
        .item-table tr.total td:nth-child(2) { border-top: 2px solid #ddd; font-weight: bold; font-size: 16px; color: #031b4e; }
        .footer { margin-top: 50px; text-align: center; color: #777; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <!-- Ensure public path image works in dompdf -->
            <img src="{{ public_path('images/logo.png') }}" alt="Vedanta Placement Agency">
            <div class="invoice-title">INVOICE / RECEIPT</div>
        </div>

        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong style="color: #031b4e; font-size: 16px;">Billed To:</strong><br>
                    {{ $user->name }}<br>
                    {{ $user->email }}<br>
                    Phone: {{ $user->phone ?? 'N/A' }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Invoice Date:</strong> {{ now()->format('F j, Y') }}<br>
                    <strong>Transaction ID:</strong> {{ $transactionId }}<br>
                    <strong>Payment Status:</strong> <span style="color: green;">PAID</span>
                </td>
            </tr>
        </table>

        <table class="item-table">
            <tr class="heading">
                <td>Description</td>
                <td style="text-align: right;">Amount</td>
            </tr>
            <tr class="item">
                <td>{{ $description }}</td>
                <td style="text-align: right;">&#8377;{{ number_format($amount, 2) }}</td>
            </tr>
            <tr class="total">
                <td style="text-align: right;">Total Paid:</td>
                <td style="text-align: right;">&#8377;{{ number_format($amount, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <strong>Vedanta Placement Agency</strong><br>
            Career Point Building, 2nd floor, Patna, 800001, Bihar<br>
            Email: info@vedantaplacementagency.in | Phone: +91-7070938975<br>
            <em>This is a computer-generated invoice and requires no physical signature.</em>
        </div>
    </div>
</body>
</html>
