<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Shortlisted</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-w-xl mx-auto p-4">
    
    <div style="background-color: #031b4e; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="color: #ffffff; margin: 0;">Congratulations!</h2>
    </div>
    
    <div style="padding: 30px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Dear <strong>{{ $application->candidate->name }}</strong>,</p>
        
        <p>We have great news! Your application for the position of <strong>{{ $application->jobPost->title }}</strong> at <strong>{{ $application->jobPost->employer->name ?? 'our partner school' }}</strong> has been reviewed and shortlisted.</p>
        
        <p>Your profile has been forwarded to the school administration. The school will directly contact you or schedule an interview with you shortly. Please keep an eye on your email and phone.</p>

        @if($application->remarks)
            <div style="background-color: #f8fafc; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0;">
                <strong>Admin Remarks:</strong><br>
                {{ $application->remarks }}
            </div>
        @endif
        
        <p>To check the status of your applications, you can log in to your candidate dashboard.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('login') }}" style="background-color: #3b82f6; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Go to Dashboard</a>
        </div>
        
        <p>Best Regards,<br>
        <strong>Vedanta Placement Agency</strong></p>
    </div>
</body>
</html>
