<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Action Required</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 16px; }
        .message { font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 24px; }
        .alert-box { background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; margin-bottom: 32px; }
        .alert-title { font-size: 14px; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .alert-content { font-size: 16px; font-weight: 600; color: #b45309; }
        .btn-container { text-align: center; margin-bottom: 32px; }
        .btn { display: inline-block; background-color: #f59e0b; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2); transition: all 0.2s; }
        .btn:hover { background-color: #d97706; box-shadow: 0 6px 16px rgba(217, 119, 6, 0.3); transform: translateY(-1px); }
        .footer { background-color: #f1f5f9; padding: 24px 40px; text-align: center; font-size: 13px; color: #64748b; }
        .contact { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Action Required</h1>
        </div>
        
        <div class="content">
            <div class="greeting">Hi {{ $user->name }},</div>
            
            <div class="message">
                We noticed that your registration process with Vedanta is currently incomplete. To finalize your onboarding and unlock access to premium job opportunities, please complete the following pending action.
            </div>

            <div class="alert-box">
                <div class="alert-title">Pending Action</div>
                <div class="alert-content">{{ $reason }}</div>
            </div>

            <div class="btn-container">
                <a href="{{ $actionUrl }}" class="btn">Complete Now</a>
            </div>

            <div class="message" style="margin-bottom: 0;">
                If you have already completed this step or need any assistance, please don't hesitate to reach out to our support team. We're here to help!
            </div>
        </div>

        <div class="footer">
            <div>&copy; {{ date('Y') }} Vedanta. All rights reserved.</div>
            <div class="contact">
                <strong>Contact Us</strong><br>
                Email: info@vedantaplacementagency.in<br>
                Phone: +91-7070938975
            </div>
        </div>
    </div>
</body>
</html>
