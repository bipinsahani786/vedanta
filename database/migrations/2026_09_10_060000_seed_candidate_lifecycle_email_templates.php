<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Helper to build consistent Vedanta-branded email HTML.
     */
    private function buildTemplateHtml(string $badge, string $badgeBg, string $badgeColor, string $headline, string $bodyContent, ?array $details = null, ?string $btnText = null, ?string $btnUrl = null): string
    {
        $detailsHtml = '';
        if ($details && count($details) > 0) {
            $rows = '';
            foreach ($details as $label => $val) {
                $rows .= "<tr><td style=\"padding: 8px 12px; font-weight: 700; color: #475569; width: 38%; border-bottom: 1px solid #e2e8f0;\">{$label}</td><td style=\"padding: 8px 12px; color: #0f172a; border-bottom: 1px solid #e2e8f0;\">{$val}</td></tr>";
            }
            $detailsHtml = <<<HTML
            <div style="margin: 22px 0; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                    {$rows}
                </table>
            </div>
HTML;
        }

        $btnHtml = '';
        if ($btnText) {
            $url = $btnUrl ?? 'https://vedantaplacementagency.in/login';
            $btnHtml = <<<HTML
            <div style="text-align: center; margin: 28px 0;">
                <a href="{$url}" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 10px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);">{$btnText}</a>
            </div>
HTML;
        }

        return <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
    <div style="background-color: #031b4e; padding: 26px 20px; text-align: center;">
        <img src="https://vedantaplacementagency.in/images/logo.png" alt="Vedanta Placement Agency" style="height: 48px; max-width: 100%; display: inline-block;">
    </div>
    <div style="background-color: {$badgeBg}; color: {$badgeColor}; padding: 10px 20px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-align: center;">
        {$badge}
    </div>
    <div style="padding: 32px 28px; color: #1e293b; font-size: 15px; line-height: 1.65;">
        <h2 style="margin-top: 0; margin-bottom: 16px; font-size: 21px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">{$headline}</h2>
        <p style="margin-bottom: 14px;">Dear <strong>{name}</strong>,</p>
        {$bodyContent}
        {$detailsHtml}
        {$btnHtml}
        <p style="margin-top: 24px; margin-bottom: 0; font-size: 14px; color: #64748b;">If you have any questions or require guidance, our recruitment support team is here to assist you.</p>
    </div>
    <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 22px 28px; font-size: 12px; color: #64748b; line-height: 1.6;">
        <strong style="color: #0f172a; font-size: 13px;">Vedanta Placement Agency</strong><br>
        Career Point Building, 2nd Floor, Patna, 800001, Bihar<br>
        <strong>Website:</strong> <a href="https://vedantaplacementagency.in" style="color: #2563eb; text-decoration: none;">vedantaplacementagency.in</a> &bull; 
        <strong>Email:</strong> <a href="mailto:info@vedantaplacementagency.in" style="color: #2563eb; text-decoration: none;">info@vedantaplacementagency.in</a> &bull; 
        <strong>Phone:</strong> +91-7070938975
        <div style="border-top: 1px solid #e2e8f0; margin-top: 12px; padding-top: 12px; text-align: center; color: #94a3b8; font-size: 11px;">
            &copy; Vedanta Placement Agency. All rights reserved.
        </div>
    </div>
</div>
HTML;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $templates = [
            // 1. Welcome to Vedanta
            [
                'name' => 'Welcome to Vedanta',
                'subject' => 'Welcome to Vedanta Placement Agency, {name}!',
                'body' => $this->buildTemplateHtml(
                    'Welcome to Vedanta', '#eff6ff', '#1d4ed8',
                    'Welcome to Your Teaching Career Partner',
                    '<p>Thank you for creating your account with <strong>Vedanta Placement Agency</strong>. We are thrilled to partner with you in discovering rewarding teaching opportunities across premier schools and educational institutions.</p><p>To start getting matched with top school vacancies tailored to your subject and category, please ensure your profile information is complete.</p>',
                    ['Candidate Name' => '{name}', 'Email' => '{email}', 'Registered Phone' => '{phone}'],
                    'Complete Your Profile', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],

            // 2. Registration Confirmation
            [
                'name' => 'Registration Confirmation',
                'subject' => 'Registration Confirmed - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Registration Confirmed', '#ecfdf5', '#047857',
                    'Your Registration is Confirmed!',
                    '<p>Your candidate registration with <strong>Vedanta Placement Agency</strong> has been officially confirmed! Our placement cell has recorded your credentials and you are now active in our talent database.</p><p>You can now browse verified school vacancies and track your applications directly from your candidate dashboard.</p>',
                    ['Candidate' => '{name}', 'Category' => '{category}', 'Subject' => '{subject}', 'Plan' => '{plan_type}'],
                    'Go to Dashboard', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 3. Profile Verification
            [
                'name' => 'Profile Verification',
                'subject' => 'Profile Verified Successfully - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Profile Verified', '#ecfdf5', '#047857',
                    'Congratulations! Your Profile is Verified',
                    '<p>We are pleased to inform you that our recruitment team has verified your credentials and submitted documents. Your candidate profile now proudly carries the <strong>Verified Candidate Badge</strong>.</p><p>Profiles with verified status receive priority review and 3x more shortlist recommendations from partner schools.</p>',
                    ['Verification Status' => '<span style="color: #059669; font-weight: 700;">Verified &#10004;</span>', 'Category' => '{category}', 'Subject' => '{subject}'],
                    'View Verified Profile', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 4. Payment Confirmation
            [
                'name' => 'Payment Confirmation',
                'subject' => 'Payment Received - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Payment Successful', '#ecfdf5', '#047857',
                    'Payment Receipt Confirmation',
                    '<p>We have successfully received your payment. Thank you for your transaction with Vedanta Placement Agency. Below are the details of your payment for your records.</p>',
                    ['Candidate' => '{name}', 'Amount Paid' => '&#8377;{payment_amount}', 'Transaction/Invoice ID' => '{invoice_number}', 'Status' => 'Success / Paid'],
                    'View Payment Details', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 5. Premium Registration Activated
            [
                'name' => 'Premium Registration Activated',
                'subject' => 'Premium Plan Activated - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Premium Member', '#fef3c7', '#b45309',
                    'Your Premium Membership is Now Active!',
                    '<p>Congratulations! Your account has been upgraded to the <strong>Premium Registration Plan</strong>. You now have access to exclusive benefits including priority school forwarding, dedicated recruiter assistance, and enhanced interview opportunities.</p>',
                    ['Plan Name' => 'Premium Plan', 'Allowed Applications' => '3 Applications', 'Dedicated Support' => 'Active'],
                    'Explore Premium Vacancies', 'https://vedantaplacementagency.in/candidate/applications/available'
                )
            ],

            // 6. Profile Completion
            [
                'name' => 'Profile Completion',
                'subject' => 'Action Required: Complete Your Profile - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Profile Action Required', '#fffbeb', '#b45309',
                    'Complete Your Profile to Unlock Applications',
                    '<p>We noticed your profile with Vedanta is still missing some key information (such as qualifications, experience, or preferred location). Partner schools require a 100% complete profile before reviewing candidates for interviews.</p><p>Please take 2 minutes to update your details and maximize your chances.</p>',
                    ['Current Category' => '{category}', 'Target Subject' => '{subject}'],
                    'Complete Profile Now', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],

            // 7. New Vacancy Match
            [
                'name' => 'New Vacancy Match',
                'subject' => 'New Vacancy Matching Your Profile: {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Job Opportunity', '#eff6ff', '#1d4ed8',
                    'New Teaching Vacancy Matching Your Profile',
                    '<p>A new teaching vacancy has just been published by a partner school that matches your subject expertise and category preferences.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Subject' => '{subject}', 'Category' => '{category}'],
                    'View & Apply Now', 'https://vedantaplacementagency.in/candidate/applications/available'
                )
            ],

            // 8. Application Received
            [
                'name' => 'Application Received',
                'subject' => 'Application Received: {job_title} - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Application Received', '#eff6ff', '#1d4ed8',
                    'We Have Received Your Application',
                    '<p>Your application for the position of <strong>{job_title}</strong> at <strong>{school_name}</strong> has been successfully received by Vedanta Placement Agency.</p><p>Our talent acquisition team will review your qualifications against the school\'s requirements and update you shortly.</p>',
                    ['Applied Position' => '{job_title}', 'Institution' => '{school_name}', 'Applicant' => '{name}'],
                    'Track Application Status', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 9. Application Submitted
            [
                'name' => 'Application Submitted',
                'subject' => 'Application Submitted Successfully: {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Application Submitted', '#ecfdf5', '#047857',
                    'Application Successfully Submitted',
                    '<p>Great news! Your application for <strong>{job_title}</strong> has been submitted. Your profile, credentials, and match score have been routed to the hiring desk for screening.</p>',
                    ['Job Title' => '{job_title}', 'School / Institution' => '{school_name}', 'Subject' => '{subject}'],
                    'View Application Details', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 10. Application Under Review
            [
                'name' => 'Application Under Review',
                'subject' => 'Your Application is Under Review: {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Under Review', '#fef3c7', '#b45309',
                    'Your Application is Currently Under Review',
                    '<p>The hiring team at <strong>{school_name}</strong> has begun reviewing your application for the <strong>{job_title}</strong> position. We will notify you as soon as the shortlist decisions are finalized.</p>',
                    ['Job Title' => '{job_title}', 'School' => '{school_name}', 'Review Status' => 'Active Reviewing'],
                    'Check Application Tracker', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 11. Application Shortlisted
            [
                'name' => 'Application Shortlisted',
                'subject' => 'Congratulations! Your Application has been Shortlisted: {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Shortlisted', '#ecfdf5', '#047857',
                    'You Have Been Shortlisted!',
                    '<p>We are delighted to share that your profile has been <strong>shortlisted</strong> for the position of <strong>{job_title}</strong> at <strong>{school_name}</strong>. Your profile has been forwarded directly to the school management.</p><p>Please prepare your demo lesson and resume. The school will schedule an interview session shortly.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Status' => 'Shortlisted & Forwarded'],
                    'View Shortlist Status', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 12. Application Not Shortlisted
            [
                'name' => 'Application Not Shortlisted',
                'subject' => 'Update on Your Application: {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Application Update', '#f1f5f9', '#475569',
                    'Update Regarding Your Application',
                    '<p>Thank you for your interest in the <strong>{job_title}</strong> position at <strong>{school_name}</strong>. The school recruitment committee has reviewed all applications and has decided to move forward with other candidates whose profiles more closely match their specific requirements at this time.</p><p>Please do not be discouraged! Your registration remains active and we are actively matching you with other prestigious teaching opportunities.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}'],
                    'Explore Other Vacancies', 'https://vedantaplacementagency.in/candidate/applications/available'
                )
            ],

            // 13. Interview Invitation
            [
                'name' => 'Interview Invitation',
                'subject' => 'Interview Invitation: {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Interview Invitation', '#eff6ff', '#1d4ed8',
                    'You Are Invited for an Interview!',
                    '<p>We are pleased to inform you that <strong>{school_name}</strong> has invited you for an interview round for the position of <strong>{job_title}</strong>.</p><p>Please confirm your availability so our recruitment coordinator can finalize the exact schedule and format (online or in-person).</p>',
                    ['Position' => '{job_title}', 'Institution' => '{school_name}', 'Candidate' => '{name}'],
                    'Confirm Availability', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 14. Interview Scheduled
            [
                'name' => 'Interview Scheduled',
                'subject' => 'Interview Scheduled: {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Interview Scheduled', '#ecfdf5', '#047857',
                    'Your Interview Has Been Scheduled',
                    '<p>Your interview for the <strong>{job_title}</strong> position at <strong>{school_name}</strong> has been officially confirmed. Please review the schedule and meeting details below.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Date & Time' => '{interview_date}', 'Meeting Link / Address' => '{interview_link}', 'Remarks' => '{remarks}'],
                    'View Interview Details', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 15. Interview Brief
            [
                'name' => 'Interview Brief',
                'subject' => 'Interview Brief & Preparation Tips: {job_title} - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Interview Brief', '#eff6ff', '#1d4ed8',
                    'Interview Brief & Preparation Guidelines',
                    '<p>To help you succeed in your upcoming interview with <strong>{school_name}</strong>, here is a quick preparation brief prepared by our recruitment experts:</p><ul><li><strong>Demo Class:</strong> Prepare a 15-20 minute lesson plan on a core topic in {subject}.</li><li><strong>Curriculum Knowledge:</strong> Familiarize yourself with CBSE/ICSE board norms and modern teaching methodologies.</li><li><strong>Attire:</strong> Formal teaching attire is strictly recommended.</li><li><strong>Documents:</strong> Carry or keep copies of your CV, degrees, and lesson plan ready.</li></ul>',
                    ['Role' => '{job_title}', 'School' => '{school_name}', 'Subject' => '{subject}'],
                    'Review Application', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 16. Interview Reminder — 24 Hours
            [
                'name' => 'Interview Reminder — 24 Hours',
                'subject' => 'Reminder: Your Interview is Scheduled for Tomorrow - {job_title}',
                'body' => $this->buildTemplateHtml(
                    '24h Reminder', '#fffbeb', '#b45309',
                    'Your Interview is Scheduled for Tomorrow',
                    '<p>This is a gentle reminder that your interview for the <strong>{job_title}</strong> position with <strong>{school_name}</strong> will take place tomorrow. Please make sure you are well prepared and have tested your connectivity or planned your travel in advance.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Scheduled Time' => '{interview_date}', 'Venue / Link' => '{interview_link}'],
                    'Check Schedule Details', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 17. Interview Reminder — Same Day
            [
                'name' => 'Interview Reminder — Same Day',
                'subject' => 'Today: Your Interview for {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Today: Interview Day', '#fef2f2', '#b91c1c',
                    'Best of Luck: Your Interview is Today!',
                    '<p>Your interview for the <strong>{job_title}</strong> position at <strong>{school_name}</strong> is scheduled for today. Please ensure you join or arrive 10-15 minutes prior to the scheduled time.</p><p>Stay confident, articulate your subject knowledge clearly, and showcase your passion for teaching!</p>',
                    ['Time' => '{interview_date}', 'Venue / Link' => '{interview_link}', 'Position' => '{job_title}'],
                    'Open Interview Link', '{interview_link}'
                )
            ],

            // 18. Interview Rescheduled
            [
                'name' => 'Interview Rescheduled',
                'subject' => 'Notice: Interview Rescheduled for {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Interview Rescheduled', '#fffbeb', '#b45309',
                    'Your Interview Has Been Rescheduled',
                    '<p>Please note that the interview schedule for the <strong>{job_title}</strong> position at <strong>{school_name}</strong> has been updated. Below are the new date and time details.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'New Date & Time' => '{interview_date}', 'Meeting Link / Venue' => '{interview_link}', 'Note' => '{remarks}'],
                    'View Updated Schedule', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 19. Interview Cancelled
            [
                'name' => 'Interview Cancelled',
                'subject' => 'Interview Cancelled: {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Interview Cancelled', '#f1f5f9', '#475569',
                    'Notice of Interview Cancellation',
                    '<p>We regret to inform you that the interview round scheduled for the position of <strong>{job_title}</strong> at <strong>{school_name}</strong> has been cancelled by the school administration due to internal scheduling changes.</p><p>Our team is following up with the school to arrange an alternate date or recommend you to other active vacancies matching your profile.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}'],
                    'View Active Vacancies', 'https://vedantaplacementagency.in/candidate/applications/available'
                )
            ],

            // 20. Interview Outcome
            [
                'name' => 'Interview Outcome',
                'subject' => 'Interview Outcome & Feedback: {job_title} - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Interview Outcome', '#eff6ff', '#1d4ed8',
                    'Interview Feedback & Next Steps',
                    '<p>The interview evaluation for your recent session with <strong>{school_name}</strong> has been completed. Please log in to your candidate dashboard to view detailed feedback from the interviewers, match notes, and next steps.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Interview Feedback' => '{remarks}'],
                    'View Detailed Outcome', 'https://vedantaplacementagency.in/candidate/applications'
                )
            ],

            // 21. Selection Confirmation
            [
                'name' => 'Selection Confirmation',
                'subject' => 'Congratulations! You are Selected for {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Selection Confirmed', '#ecfdf5', '#047857',
                    'Hearty Congratulations! You Have Been Selected',
                    '<p>We are thrilled to announce that you have been officially selected for the position of <strong>{job_title}</strong> at <strong>{school_name}</strong>!</p><p>The school management was thoroughly impressed with your demonstration, communication, and subject expertise. Please review the selection instructions and service charge invoice on your dashboard.</p>',
                    ['Designation' => '{job_title}', 'Institution' => '{school_name}', 'Candidate' => '{name}', 'Selection Status' => '<span style="color: #059669; font-weight: 700;">Hired / Selected &#10004;</span>'],
                    'View Selection & Invoice', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 22. Selection Confirmation Request
            [
                'name' => 'Selection Confirmation Request',
                'subject' => 'Action Required: Confirm Acceptance of Selection - {job_title}',
                'body' => $this->buildTemplateHtml(
                    'Action Required', '#fffbeb', '#b45309',
                    'Please Confirm Your Acceptance of Selection',
                    '<p>Following your successful interview with <strong>{school_name}</strong> for the position of <strong>{job_title}</strong>, the school is eager to formalize your placement. Please confirm your acceptance of the offer within 48 hours so that your joining formalities can be initiated.</p>',
                    ['Position Offered' => '{job_title}', 'School' => '{school_name}', 'Response Window' => 'Within 48 Hours'],
                    'Confirm Acceptance Now', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 23. Offer Letter Available
            [
                'name' => 'Offer Letter Available',
                'subject' => 'Your Offer Letter is Ready: {job_title} - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Offer Letter Available', '#ecfdf5', '#047857',
                    'Your Official Offer Letter is Ready',
                    '<p>We are delighted to share that your formal <strong>Offer Letter</strong> for <strong>{job_title}</strong> at <strong>{school_name}</strong> has been issued. Please download, review the terms, and upload your signed copy to confirm your joining.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Document' => 'Appointment / Offer Letter'],
                    'View & Download Offer Letter', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 24. Offer Letter Reminder
            [
                'name' => 'Offer Letter Reminder',
                'subject' => 'Reminder: Review and Sign Your Offer Letter - {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Offer Letter Reminder', '#fffbeb', '#b45309',
                    'Reminder: Please Sign and Return Your Offer Letter',
                    '<p>This is a follow-up reminder regarding your offer letter for the <strong>{job_title}</strong> position at <strong>{school_name}</strong>. The school requires your signed confirmation to finalize staff allocation for the upcoming academic session.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Action' => 'Sign & Upload Offer Letter'],
                    'Upload Signed Letter', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 25. Joining Instructions
            [
                'name' => 'Joining Instructions',
                'subject' => 'Joining Instructions & Guidelines: {job_title} at {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Joining Instructions', '#eff6ff', '#1d4ed8',
                    'Joining Instructions & Day-1 Guidelines',
                    '<p>As you prepare to begin your new journey as <strong>{job_title}</strong> with <strong>{school_name}</strong>, please find your reporting guidelines below:</p><ul><li><strong>Reporting Time:</strong> 08:30 AM on your scheduled joining date.</li><li><strong>Contact Person:</strong> School Principal / Administrative Officer.</li><li><strong>Documents to Carry:</strong> Original certificates, 2 passport-size photos, Aadhaar Card, and signed appointment letter.</li><li><strong>Dress Code:</strong> Formal teaching attire.</li></ul>',
                    ['Role' => '{job_title}', 'School' => '{school_name}', 'Candidate' => '{name}'],
                    'View Joining Details', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 26. Joining Date Reminder
            [
                'name' => 'Joining Date Reminder',
                'subject' => 'Reminder: Your Joining Date is Approaching - {school_name}',
                'body' => $this->buildTemplateHtml(
                    'Joining Reminder', '#ecfdf5', '#047857',
                    'Your Joining Date is Just Around the Corner!',
                    '<p>This is an exciting reminder that your official joining date at <strong>{school_name}</strong> is coming up soon. We wish you an inspiring and successful start to your teaching tenure!</p><p>Please make sure all onboarding documents and travel arrangements are finalized in advance.</p>',
                    ['Designation' => '{job_title}', 'School' => '{school_name}'],
                    'View Details on Dashboard', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 27. Joining Confirmed
            [
                'name' => 'Joining Confirmed',
                'subject' => 'Joining Confirmed - Welcome to {school_name}!',
                'body' => $this->buildTemplateHtml(
                    'Joining Confirmed', '#ecfdf5', '#047857',
                    'Joining Confirmed - Welcome to the School!',
                    '<p>We are delighted to receive confirmation from <strong>{school_name}</strong> that you have officially joined your role as <strong>{job_title}</strong>!</p><p>On behalf of the entire team at Vedanta Placement Agency, we congratulate you on your placement and wish you a stellar career ahead.</p>',
                    ['Placed Candidate' => '{name}', 'Role' => '{job_title}', 'School' => '{school_name}', 'Status' => 'Successfully Joined &#127881;'],
                    'Visit Dashboard', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 28. Documents Required
            [
                'name' => 'Documents Required',
                'subject' => 'Documents Required for Verification - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Documents Required', '#fffbeb', '#b45309',
                    'Action Required: Please Upload Necessary Documents',
                    '<p>To proceed with your verification and school forwarding, we require you to upload clear copies of your verification documents. Please log in to your profile and upload the following documents at your earliest convenience:</p><ul><li>Updated Curriculum Vitae (CV)</li><li>Graduation / Post-Graduation Marksheets & Degree Certificates</li><li>B.Ed / D.El.Ed / Teaching Qualification Certificates</li><li>Identity Proof (Aadhaar Card / PAN Card)</li><li>Recent Passport Size Photograph</li></ul>',
                    ['Candidate' => '{name}', 'Category' => '{category}', 'Subject' => '{subject}'],
                    'Upload Documents Now', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],

            // 29. Missing Documents
            [
                'name' => 'Missing Documents',
                'subject' => 'Urgent: Missing Documents in Your Profile - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Missing Documents', '#fef2f2', '#b91c1c',
                    'Notice: Some Required Documents are Missing',
                    '<p>Our verification desk was reviewing your profile and noticed that one or more required documents (e.g. B.Ed Degree, Experience Letter, or Photo ID) are missing or illegible.</p><p>Please re-upload the missing documents to prevent any delays in your shortlist forwarding to partner schools.</p>',
                    ['Candidate' => '{name}', 'Status' => 'Pending Re-upload'],
                    'Upload Missing Files', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],

            // 30. Documents Verified
            [
                'name' => 'Documents Verified',
                'subject' => 'All Documents Successfully Verified - Vedanta Placement Agency',
                'body' => $this->buildTemplateHtml(
                    'Documents Verified', '#ecfdf5', '#047857',
                    'Your Documents Have Been Verified Successfully',
                    '<p>All the documents and certificates you submitted have been thoroughly reviewed and approved by our verification officers. Your candidate profile is now fully credentialed and cleared for priority placement across partner schools.</p>',
                    ['Document Status' => '<span style="color: #059669; font-weight: 700;">Verified & Cleared &#10004;</span>', 'Candidate' => '{name}'],
                    'View Verified Profile', 'https://vedantaplacementagency.in/candidate/dashboard'
                )
            ],

            // 31. Additional Documents Required
            [
                'name' => 'Additional Documents Required',
                'subject' => 'Action Required: Additional Documents Requested - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Additional Documents', '#fffbeb', '#b45309',
                    'Additional Documents Requested by School',
                    '<p>The hiring committee at <strong>{school_name}</strong> has requested additional documentation (such as previous salary slips, experience letters, or specialization certificates) to support your application for <strong>{job_title}</strong>.</p><p>Please upload these requested files promptly so we can fast-track your final offer.</p>',
                    ['Position' => '{job_title}', 'School' => '{school_name}', 'Requested Info' => '{remarks}'],
                    'Upload Additional Documents', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],

            // 32. Final Document Reminder
            [
                'name' => 'Final Document Reminder',
                'subject' => 'Final Notice: Urgent Document Submission Required - Vedanta',
                'body' => $this->buildTemplateHtml(
                    'Final Reminder', '#fef2f2', '#b91c1c',
                    'Final Notice: Pending Document Submission',
                    '<p>This is a final reminder regarding the submission of your pending documents. Failure to submit the required verification files within 24 hours may lead to cancellation of your ongoing interview or job application.</p><p>Please complete this step immediately to protect your candidature.</p>',
                    ['Candidate' => '{name}', 'Deadline' => 'Within 24 Hours', 'Action Needed' => 'Upload Pending Verification Documents'],
                    'Submit Documents Immediately', 'https://vedantaplacementagency.in/candidate/wizard'
                )
            ],
        ];

        foreach ($templates as $t) {
            DB::table('email_templates')->updateOrInsert(
                ['name' => $t['name']],
                [
                    'subject' => $t['subject'],
                    'body' => $t['body'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $names = [
            'Welcome to Vedanta',
            'Registration Confirmation',
            'Profile Verification',
            'Payment Confirmation',
            'Premium Registration Activated',
            'Profile Completion',
            'New Vacancy Match',
            'Application Received',
            'Application Submitted',
            'Application Under Review',
            'Application Shortlisted',
            'Application Not Shortlisted',
            'Interview Invitation',
            'Interview Scheduled',
            'Interview Brief',
            'Interview Reminder — 24 Hours',
            'Interview Reminder — Same Day',
            'Interview Rescheduled',
            'Interview Cancelled',
            'Interview Outcome',
            'Selection Confirmation',
            'Selection Confirmation Request',
            'Offer Letter Available',
            'Offer Letter Reminder',
            'Joining Instructions',
            'Joining Date Reminder',
            'Joining Confirmed',
            'Documents Required',
            'Missing Documents',
            'Documents Verified',
            'Additional Documents Required',
            'Final Document Reminder',
        ];

        DB::table('email_templates')->whereIn('name', $names)->delete();
    }
};
