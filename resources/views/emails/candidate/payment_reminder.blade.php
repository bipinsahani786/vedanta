<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Reminder</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { background-color: #0f172a; padding: 30px; text-align: center; }
        .header img { max-height: 50px; }
        .content { padding: 40px; }
        .title { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 20px; text-align: center; }
        .text { font-size: 16px; line-height: 1.6; color: #4b5563; margin-bottom: 20px; }
        .invoice-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 25px 0; }
        .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; }
        .invoice-row:last-child { margin-bottom: 0; padding-top: 10px; border-top: 1px solid #e2e8f0; font-weight: bold; color: #111827; }
        .label { color: #64748b; }
        .value { color: #1e293b; font-weight: 600; }
        .value.overdue { color: #dc2626; }
        .btn-container { text-align: center; margin: 35px 0 20px; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; transition: background-color 0.2s; }
        .btn:hover { background-color: #1d4ed8; }
        .footer { background-color: #f8fafc; padding: 25px; text-align: center; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency">
        </div>
        
        <div class="content">
            <h1 class="title">Payment Reminder</h1>
            
            <p class="text">Dear {{ $user->name }},</p>
            <p class="text">This is a friendly reminder regarding your pending payment for <strong>Service Charge</strong> at Vedanta Placement Agency.</p>
            
            <div class="invoice-box">
                <div class="invoice-row">
                    <span class="label">Invoice ID:</span>
                    <span class="value">INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="invoice-row">
                    <span class="label">Related Job:</span>
                    <span class="value">{{ $invoice->jobApplication?->jobPost?->title ?? 'Service Charge' }}</span>
                </div>
                <div class="invoice-row">
                    <span class="label">Base Amount:</span>
                    <span class="value">₹{{ number_format($invoice->amount, 2) }}</span>
                </div>
                @if($invoice->late_fee > 0)
                <div class="invoice-row">
                    <span class="label">Late Fee:</span>
                    <span class="value overdue">₹{{ number_format($invoice->late_fee, 2) }}</span>
                </div>
                @endif
                <div class="invoice-row">
                    <span class="label">Due Date:</span>
                    <span class="value {{ $invoice->status === 'overdue' ? 'overdue' : '' }}">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                </div>
                <div class="invoice-row">
                    <span class="label">Total Amount Due:</span>
                    <span class="value">₹{{ number_format($invoice->amount + $invoice->late_fee, 2) }}</span>
                </div>
            </div>

            <p class="text">To avoid further late fees or temporary suspension of your profile, please complete the payment at your earliest convenience.</p>

            <div class="btn-container">
                <a href="{{ route('candidate.serviceCharge.show') }}" class="btn">Pay Now</a>
            </div>
            
            <p class="text" style="font-size: 14px; margin-top: 30px;">If you have already made the payment, please ignore this email or contact our support team.</p>
        </div>
        
        <div class="footer">
            <h3 style="margin-top:0; color:#1e293b;">Contact Us</h3>
            <p style="margin: 5px 0;"><strong>Vedanta Placement Agency</strong></p>
            <p style="margin: 5px 0;">Career Point Building, 2nd floor, Patna, 800001, Bihar</p>
            <p style="margin: 15px 0;">
                <a href="https://vedantaplacementagency.in" style="color: #2563eb; text-decoration: none;">Website</a> &nbsp;|&nbsp; 
                <a href="mailto:info@vedantaplacementagency.in" style="color: #2563eb; text-decoration: none;">info@vedantaplacementagency.in</a> &nbsp;|&nbsp; 
                +91-7070938975
            </p>
            <p style="margin-top: 25px; font-size: 12px; color:#94a3b8;">&copy; {{ date('Y') }} Vedanta Placement Agency. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
