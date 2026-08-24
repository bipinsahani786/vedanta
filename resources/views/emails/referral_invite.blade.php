<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>You're invited to Vedanta Placement Agency</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0b1528; color: #ffffff; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #132238; border-radius: 16px; border: 1px solid #1e3a5f; overflow: hidden; }
        .header { background: linear-gradient(135deg, #129aef, #0866c6); padding: 30px 20px; text-align: center; }
        .content { padding: 30px 25px; color: #e2e8f0; }
        .code-box { background: #0b1528; border: 2px dashed #129aef; border-radius: 12px; padding: 15px; text-align: center; margin: 20px 0; }
        .btn { display: inline-block; background: #129aef; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: bold; margin-top: 15px; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">Vedanta Placement Agency</h1>
            <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px;">Your Gateway to Premier Teaching Careers</p>
        </div>
        <div class="content">
            <h2 style="color: #ffffff; margin-top: 0;">Hi {{ $invite->friend_name }},</h2>
            <p><strong>{{ $referrer->name }}</strong> has invited you to create your candidate profile on <strong>Vedanta Placement Agency</strong> — India's leading education recruitment platform connecting teachers and staff to top schools across India.</p>

            @if($personalNote)
                <div style="background: rgba(18, 154, 239, 0.1); border-left: 4px solid #129aef; padding: 12px 16px; margin: 15px 0; border-radius: 4px; font-style: italic; color: #93c5fd;">
                    "{{ $personalNote }}"
                </div>
            @endif

            <p>Use {{ $referrer->name }}'s referral code during registration to unlock welcome perks and get priority placement review:</p>

            <div class="code-box">
                <div style="font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Referral Code</div>
                <div style="font-size: 24px; font-weight: bold; color: #38bdf8; letter-spacing: 2px; margin-top: 5px;">{{ $referrer->referral_code }}</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $referrer->referral_link }}" class="btn">Accept Invitation & Register Now</a>
            </div>

            <ul style="margin-top: 25px; padding-left: 20px; color: #94a3b8; font-size: 13px; line-height: 1.6;">
                <li>Explore 20,000+ verified school & college vacancies</li>
                <li>Direct interview matching with school principals</li>
                <li>Dedicated support for your teaching career</li>
            </ul>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Vedanta Placement Agency. All rights reserved.<br>
            If you did not expect this invitation, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
