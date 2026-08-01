<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Interview Review</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { background-color: #0f172a; padding: 30px; text-align: center; }
        .header img { max-height: 50px; }
        .content { padding: 40px; }
        .title { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 20px; text-align: center; }
        .text { font-size: 16px; line-height: 1.6; color: #4b5563; margin-bottom: 20px; }
        .review-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin: 25px 0; }
        .label { color: #64748b; font-size: 14px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 8px; display: block; }
        .value { color: #1e293b; font-size: 16px; font-weight: 500; margin-bottom: 20px; }
        .remarks { background-color: #ffffff; border-left: 4px solid #2563eb; padding: 15px; color: #334155; font-style: italic; margin-top: 10px; }
        .footer { background-color: #f8fafc; padding: 25px; text-align: center; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency">
        </div>
        
        <div class="content">
            <h1 class="title">Interview Feedback</h1>
            
            <p class="text">Dear {{ $application->candidate->name }},</p>
            <p class="text">We have received feedback regarding your recent interview for the <strong>{{ $application->jobPost->title }}</strong> position.</p>
            
            <div class="review-box">
                <span class="label">Job Role:</span>
                <div class="value">{{ $application->jobPost->title }}</div>
                
                <span class="label">Status:</span>
                <div class="value">
                    @if($application->status === 'shortlisted') Forwarded
                    @elseif($application->status === 'hired') Selected
                    @elseif($application->status === 'rejected') Rejected
                    @else Under Review
                    @endif
                </div>

                <span class="label">Interview Review / Remarks:</span>
                <div class="remarks">
                    {!! nl2br(e($application->remarks)) !!}
                </div>
            </div>

            <p class="text">If you have any questions or need further assistance, please feel free to contact us.</p>
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
