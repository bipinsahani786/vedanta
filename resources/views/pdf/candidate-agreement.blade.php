<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidate Agreement - {{ $user->name ?? 'Vedanta' }}</title>
    <style>
        @page {
            margin: 18px 24px 22px 24px;
            size: a4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 7.7pt;
            line-height: 1.29;
            margin: 0;
            padding: 0;
        }

        /* Center Watermark on every page without frame disruption */
        .watermark-bg {
            position: fixed;
            top: 30%;
            left: 0;
            right: 0;
            text-align: center;
            height: 0;
            z-index: -1000;
        }
        .watermark-img {
            width: 410px;
            opacity: 0.11;
        }

        /* Running Footer on every page using table to avoid DomPDF float bugs */
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 14px;
            border-top: 1px solid #d1d5db;
            padding-top: 2px;
        }
        .footer table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .footer td {
            border: none;
            padding: 0;
            font-size: 6.8pt;
        }
        .footer-left {
            text-align: left;
            font-weight: bold;
            color: #15803d;
            letter-spacing: 0.3px;
        }
        .footer-right {
            text-align: right;
            color: #4b5563;
        }
        .page-num:after {
            content: counter(page);
        }

        /* Header Style */
        .header-section {
            text-align: center;
            border-bottom: 1.5px solid #004d99;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }
        .logo-img {
            height: 48px;
            margin-bottom: 2px;
        }
        .main-title {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .sub-service {
            font-size: 9.5px;
            font-weight: bold;
            color: #374151;
            letter-spacing: 0.8px;
            margin-top: 1px;
            text-transform: uppercase;
        }
        .sub-desc {
            font-size: 7.2pt;
            font-weight: bold;
            color: #374151;
            margin-top: 1px;
        }
        .sub-intent {
            font-size: 6.8pt;
            color: #4b5563;
            margin-top: 1px;
            margin-bottom: 3px;
        }

        /* Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3.5px;
        }
        .info-table th, .info-table td {
            border: 1px solid #285e33;
            padding: 2.2px 4.5px;
            font-size: 6.8pt;
            vertical-align: middle;
        }
        .info-table th {
            background-color: #f4faf6;
            color: #004d99;
            font-weight: bold;
            width: 32%;
            text-align: left;
        }
        .info-table td {
            color: #1f2937;
            width: 68%;
        }

        /* Photo verification container */
        .photo-verification-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            border: 1px solid #285e33;
        }
        .photo-verification-header {
            background-color: #004d99;
            color: #ffffff;
            font-weight: bold;
            font-size: 8.2pt;
            text-align: center;
            padding: 3px;
            letter-spacing: 0.4px;
        }
        .photo-cell {
            width: 48%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
            border-right: 1px solid #285e33;
        }
        .verified-cell {
            width: 52%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }
        .candidate-photo {
            width: 135px;
            max-width: 145px;
            max-height: 105px;
            border: 1.5px solid #004d99;
            border-radius: 4px;
            object-fit: cover;
        }
        .photo-caption {
            font-size: 6.5pt;
            color: #004d99;
            margin-top: 3px;
            font-weight: bold;
        }
        .verified-badge {
            color: #15803d;
            font-weight: bold;
            font-size: 11pt;
            letter-spacing: 0.4px;
        }
        .verified-sub {
            font-size: 7.2pt;
            color: #4b5563;
            margin-top: 2px;
        }
        .verified-id {
            font-size: 7.2pt;
            font-weight: bold;
            color: #111827;
            margin-top: 2px;
        }
        .verified-status {
            font-size: 7.2pt;
            font-weight: bold;
            color: #15803d;
            margin-top: 2px;
        }

        /* Section Headings */
        .section-header {
            font-size: 8.2pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin-top: 5.2px;
            margin-bottom: 2.2px;
            letter-spacing: 0.2px;
            page-break-after: avoid;
            break-after: avoid;
        }
        .major-section-banner {
            font-size: 8.5pt;
            font-weight: bold;
            color: #111827;
            text-align: center;
            text-transform: uppercase;
            margin-top: 6px;
            margin-bottom: 4px;
            letter-spacing: 0.4px;
            border-top: 1px solid #004d99;
            border-bottom: 1px solid #004d99;
            padding: 3px 0;
            background-color: #f8fafc;
            page-break-after: avoid;
            break-after: avoid;
        }
        .clause-title {
            font-weight: bold;
            color: #111827;
        }
        p {
            margin: 0 0 3.8px 0;
            text-align: justify;
        }

        /* Signatures block */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        .signature-table th, .signature-table td {
            border: 1px solid #285e33;
            vertical-align: top;
            padding: 4px 6px;
        }
        .signature-table th {
            background-color: #f4faf6;
            color: #004d99;
            font-size: 7.2pt;
            font-weight: bold;
            width: 50%;
            text-align: left;
        }
        .signature-box {
            text-align: left;
            min-height: 44px;
        }
        .cursive-signature {
            font-family: 'Brush Script MT', 'Great Vibes', 'Comic Sans MS', cursive;
            font-size: 21px;
            color: #004d99;
            font-style: italic;
        }
        .green-verification-box {
            background-color: #f0fdf4;
            border: 1px solid #16a34a;
            border-radius: 3px;
            padding: 4px 8px;
            margin-top: 4px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/logo_transparent.png');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $adminSignPath = public_path('images/aditya_rajveer_signature.png');
        $adminSignBase64 = '';
        if (file_exists($adminSignPath)) {
            $adminSignBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($adminSignPath));
        }

        if (empty($profile->vpa_id) || ($profile->is_agreement_signed && empty($profile->agreement_id))) {
            $profile->ensureIdsAssigned();
        }

        $candidateId = $profile->vpa_id ?: ('VPA-' . date('Y') . '-' . str_pad($user->id, 3, '0', STR_PAD_LEFT));
        $agreementId = $profile->agreement_id ?: str_replace('VPA-', 'VPA-AGR-', $candidateId);
        
        $agreementDateTime = $profile->signature_date_time 
            ? \Carbon\Carbon::parse($profile->signature_date_time)->format('d/m/Y H:i:s') . ' IST' 
            : \Carbon\Carbon::now()->format('d/m/Y H:i:s') . ' IST';
    @endphp

    {{-- Center Watermark on every page --}}
    @if($logoBase64)
        <div class="watermark-bg">
            <img src="{{ $logoBase64 }}" class="watermark-img" alt="Vedanta Watermark">
        </div>
    @endif

    {{-- Running Footer on every page --}}
    <div class="footer">
        <table>
            <tr>
                <td class="footer-left">DIGITALLY SIGNED &amp; VERIFIED</td>
                <td class="footer-right">
                    Candidate ID: {{ $candidateId }} | Agreement ID: {{ $agreementId }} | <span class="page-num"></span>
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================== PAGE 1 ============================== --}}
    <div class="header-section">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" class="logo-img" alt="Vedanta Logo"><br>
        @endif
        <h1 class="main-title">VEDANTA PLACEMENT AGENCY</h1>
        <div class="sub-service">SERVICE AGREEMENT</div>
        <div class="sub-desc">Recruitment, Registration, Placement Services &amp; Candidate Terms and Conditions</div>
        <div class="sub-intent">THIS AGREEMENT is intended for online/digital execution and governs the recruitment and placement-service relationship between the Agency and the Candidate.</div>
    </div>

    <table class="info-table">
        <tr>
            <th>AGENCY</th>
            <td>Vedanta Placement Agency</td>
        </tr>
        <tr>
            <th>AUTHORIZED SIGNATORY</th>
            <td>Aditya Rajveer &mdash; Founder &amp; CEO</td>
        </tr>
        <tr>
            <th>CANDIDATE NAME</th>
            <td><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <th>CANDIDATE ID</th>
            <td><strong>{{ $candidateId }}</strong></td>
        </tr>
        <tr>
            <th>AGREEMENT ID</th>
            <td><strong>{{ $agreementId }}</strong></td>
        </tr>
        {{-- Registration plan removed as requested --}}
        <tr>
            <th>REGISTERED MOBILE</th>
            <td>{{ $user->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>REGISTERED EMAIL</th>
            <td>{{ $user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>AGREEMENT DATE / TIME</th>
            <td>{{ $agreementDateTime }}</td>
        </tr>
        <tr>
            <th>EXECUTION MODE</th>
            <td>Online / Digital Only</td>
        </tr>
        <tr>
            <th>AGREEMENT STATUS</th>
            <td><strong style="color: #15803d;">Active</strong></td>
        </tr>
        <tr>
            <th>PROFILE VERIFICATION STATUS</th>
            <td><strong style="color: #15803d;">Verified</strong></td>
        </tr>
    </table>

    {{-- Candidate Live Photo verification container --}}
    <table class="photo-verification-box">
        <tr>
            <td colspan="2" class="photo-verification-header">CANDIDATE PHOTO (LIVE)</td>
        </tr>
        <tr>
            <td class="photo-cell">
                @php
                    $photoPath = null;
                    if (!empty($profile->live_photo_path)) {
                        $p = Storage::disk('public')->path($profile->live_photo_path);
                        if (file_exists($p)) $photoPath = $p;
                    }
                    if (!$photoPath && !empty($profile->profile_photo_path)) {
                        $p = Storage::disk('public')->path($profile->profile_photo_path);
                        if (file_exists($p)) $photoPath = $p;
                    }

                    $photoBase64 = '';
                    if ($photoPath && file_exists($photoPath)) {
                        $photoExt = pathinfo($photoPath, PATHINFO_EXTENSION);
                        $photoBase64 = 'data:image/' . $photoExt . ';base64,' . base64_encode(file_get_contents($photoPath));
                    }
                @endphp

                @if($photoBase64)
                    <img src="{{ $photoBase64 }}" class="candidate-photo" alt="Live Photo">
                @else
                    <div style="width: 70px; height: 75px; border: 1px dashed #9ca3af; display: inline-block; line-height: 75px; color: #6b7280; font-size: 6pt;">
                        [Live Photo]
                    </div>
                @endif
                <div class="photo-caption">Live photograph captured for candidate profile verification</div>
            </td>
            <td class="verified-cell">
                <div class="verified-badge">&#10003; VERIFIED PROFILE</div>
                <div class="verified-sub">Candidate photograph verification</div>
                <div class="verified-id">Candidate ID: {{ $candidateId }}</div>
                <div class="verified-status">Verification Status: VERIFIED</div>
            </td>
        </tr>
    </table>

    <div class="section-header">RECITALS. BACKGROUND AND INTENT</div>
    <p>A. The Agency provides recruitment and placement facilitation services to candidates and schools/employers. B. The Candidate wishes to use the Agency’s recruitment and placement services. C. The parties intend that their relationship be governed by this Agreement together with applicable vacancy, application, placement, fee, invoice, offer/joining and digital records. D. This Agreement is a general/master agreement and is not limited to one vacancy, employer, designation, location or salary.</p>

    <div class="section-header">1. DEFINITIONS AND INTERPRETATION</div>
    <p>For this Agreement, the following terms shall have the meanings below unless the context requires otherwise.</p>
    <p><span class="clause-title">1.1 &ldquo;Agency&rdquo;</span> means Vedanta Placement Agency and its authorized personnel, representatives or service systems acting within the scope of its services.</p>
    <p><span class="clause-title">1.2 &ldquo;Candidate&rdquo;</span> means the person whose details are recorded under the Candidate ID and who accepts this Agreement.</p>
    <p><span class="clause-title">1.3 &ldquo;Employer&rdquo; or &ldquo;School&rdquo;</span> means a school, educational institution, organization or other prospective employer to whom the Candidate may be introduced or whose vacancy is facilitated by the Agency.</p>
    <p><span class="clause-title">1.4 &ldquo;Placement&rdquo;</span> means recruitment facilitation that results in the Candidate being selected, offered or joining an opportunity facilitated by the Agency.</p>
    <p><span class="clause-title">1.5 &ldquo;Service Fee&rdquo;</span> means the applicable placement/service charge stated in this Agreement or in a written/digital placement record.</p>
    <p><span class="clause-title">1.6 &ldquo;Digital Record&rdquo;</span> includes electronic forms, portal records, emails, WhatsApp messages, system logs, electronic signatures, digital photographs, consent records, invoices and other electronic records maintained in connection with the recruitment process.</p>
    <p><span class="clause-title">1.7</span> Headings are for convenience only and shall not control interpretation. References to singular include plural and vice versa where appropriate. References to applicable law include amendments, replacements and subordinate legislation in force from time to time.</p>

    <div class="section-header">2. PURPOSE AND SCOPE</div>
    <p>This Agreement governs the Candidate’s registration, recruitment support, placement facilitation, service-fee obligations, digital processing, communications, data processing and related responsibilities. It is not restricted to any particular school, employer, vacancy, designation, subject, location, salary, interview date or joining date. Specific placement details may be recorded separately through vacancy records, applications, interview communications, placement records, invoices, offer/joining records or other digital communications.</p>

    <div class="section-header">3. AUTHORIZATION FOR RECRUITMENT AND PLACEMENT</div>
    <p>By accepting this Agreement online, the Candidate authorizes the Agency to register and maintain the Candidate profile; review qualifications and experience; identify relevant opportunities; share the Candidate’s CV/profile and relevant professional information with prospective schools/employers; coordinate interviews, tests and demonstrations; facilitate recruitment communication; communicate relevant updates; and maintain recruitment and placement records.</p>

    <div class="section-header">4. AGENCY ROLE AND STANDARD OF SERVICE</div>
    <p>The Agency’s principal role is recruitment and placement facilitation. The Agency will make reasonable efforts to identify and facilitate suitable opportunities based on the Candidate’s profile and requirements received from schools/employers. The Agency may provide vacancy information, coordinate recruitment communications, facilitate interviews and provide reasonable administrative support within the scope of its services.</p>

    <div class="section-header">5. PLACEMENT PROCESS AND EMPLOYMENT DECISION</div>
    <p>The Agency will provide reasonable recruitment assistance. The final decision regarding shortlisting, interview outcome, selection, salary, designation, location, joining date, employment conditions, probation and continuation remains with the concerned school/employer according to its requirements and the terms mutually agreed with the Candidate. Registration or participation in recruitment should therefore not be understood as an assurance that employment will necessarily be offered. This clause clarifies the allocation of decision-making responsibility and does not remove any obligation expressly undertaken by the Agency in writing.</p>

    <div class="section-header">6. EMPLOYER&ndash;CANDIDATE EMPLOYMENT RELATIONSHIP</div>
    <p>After selection and joining, the employment relationship is primarily between the Candidate and the concerned school/employer. Salary, duties, working hours, leave, accommodation, benefits, workplace conditions, performance, continuation, resignation and termination are generally governed by the employment arrangement between Candidate and employer and applicable law. The Agency is not the employer merely because it facilitated recruitment.</p>

    <div class="section-header">7. EMPLOYER PROMISES, REPRESENTATIONS AND CHANGES</div>
    <p>Where a school/employer independently promises or represents salary, accommodation, food, facilities, benefits, responsibilities or working conditions, responsibility for fulfilling that commitment ordinarily remains with the concerned employer. The Agency may assist with communication or clarification where reasonably possible, but does not automatically guarantee an employer’s independent commitments unless the Agency expressly undertakes such responsibility in writing.</p>

    <div class="section-header">8. SALARY RESPONSIBILITY AND RECOVERY</div>
    <p>Salary and employment remuneration are the responsibility of the concerned school/employer. The Agency does not independently guarantee salary payment unless a separate written arrangement expressly provides otherwise. Salary recovery claims ordinarily remain between Candidate and employer. The Agency may assist with communication where appropriate but is not a salary-recovery guarantor.</p>

    <div class="section-header">9. PERSONAL SAFETY AND SECURITY</div>
    <p>The Agency is not a personal security provider, guarantor or insurer for the Candidate. The Candidate should take reasonable precautions concerning personal safety, belongings, documents, travel and conduct. The employer remains responsible for its own workplace, accommodation and employment obligations as required by applicable law. Nothing in this clause excludes a responsibility that cannot lawfully be excluded.</p>

    <div class="major-section-banner">REGISTRATION, PLACEMENT FEES &amp; PAYMENT TERMS</div>

    <div class="section-header">10. STANDARD REGISTRATION PLAN</div>
    <p>The Standard Registration Plan requires an initial registration payment of &#8377;500. A further &#8377;500 becomes payable after the interview process is successfully finalized and before the relevant offer letter is issued, subject to the applicable registration record. The Standard Plan is valid for up to 3 job applications/interview processes, as recorded by the Agency.</p>

    <div class="section-header">10.1. STANDARD PLAN PROCESSING</div>
    <p>The Agency may commence the standard process within its stated operational timeline. Registration validity is based on permitted usage and is not merely time-based.</p>

    <div class="section-header">11. PREMIUM REGISTRATION PLAN</div>
    <p>The Premium Registration Plan requires a one-time registration payment of &#8377;1,000. It provides priority processing, including same-day profile verification and process initiation where operationally available. It is valid for up to 3 job applications/interview processes. No additional registration fee is payable under the Premium Plan.</p>

    <div class="section-header">12. REGISTRATION FEE TERMS</div>
    <p>Registration fees are non-refundable except where otherwise required by applicable law or expressly approved by the Agency in writing. Payment of registration does not guarantee interview, selection or employment. Renewal may be required after permitted usage.</p>

    <div class="section-header">12.1 REGISTRATION VALIDITY AND APPLICATION LIMIT</div>
    <p>Each Standard or Premium registration is valid for up to 3 job applications/interview processes, as recorded by the Agency. Validity is usage-based rather than merely time-based. Once the permitted applications/interview processes have been used, renewal may be required. If a Candidate is selected and confirms joining, the relevant registration usage is treated as completed/closed for that placement unless the Agency confirms otherwise.</p>

    <div class="section-header">13. TEACHING PLACEMENT SERVICE FEE</div>
    <p>For a successful teaching placement, the applicable placement/service fee is 50% of one month’s gross salary/remuneration, approximately equivalent to 15 days of monthly remuneration, unless a different fee is expressly confirmed in writing for that placement.</p>

    <div class="section-header">13.1 NO SERVICE FEE BEFORE SELECTION / JOINING</div>
    <p>No placement/service fee is payable merely for registration, vacancy sharing, application submission, shortlisting or interview participation. The applicable placement/service fee becomes payable only in accordance with the applicable placement record after successful placement/joining and the payment terms stated in this Agreement.</p>

    <div class="section-header">14. MANAGEMENT / ADMINISTRATIVE / NON-TEACHING SERVICE FEE</div>
    <p>For management, administrative or other non-teaching placements, the applicable placement/service fee is 66.67% of one month’s gross salary/remuneration, approximately equivalent to 20 days of monthly remuneration, unless a different fee is expressly confirmed in writing for that placement.</p>

    <div class="section-header">15. SERVICE-FEE PAYMENT TIMELINE</div>
    <p>The applicable Service Fee is ordinarily due within 12 hours after the Candidate receives or is credited with the first salary/remuneration payment. The Candidate shall not intentionally conceal, delay or misrepresent receipt of the first salary for the purpose of avoiding or delaying payment.</p>

    <div class="section-header">15.1 LATE / OVERDUE PAYMENT CHARGES</div>
    <p>If any Service Fee or other contractually due amount remains unpaid after its due date, a late charge of &#8377;300 per calendar day shall apply from the day immediately following the due date until the outstanding amount is paid, together with simple interest at 36% per annum on the overdue Service Fee amount, calculated for the period of actual delay. These charges are subject to applicable law and shall be reduced or not enforced to the extent a competent authority determines that any part is not legally recoverable.</p>

    <div class="section-header">16. LATE PAYMENT AND DEMANDS</div>
    <p>Where an amount becomes due and remains unpaid, the Agency may issue digital reminders and formal payment notices. Any late-payment charge or contractual compensation must be stated in the applicable written/digital demand and remains subject to applicable law. The Agency shall not use unlawful, threatening, coercive or intimidating recovery methods.</p>

    <div class="section-header">17. EMPLOYER / SCHOOL DIRECT INVOICE</div>
    <p>Where commercially agreed, the Agency may issue its placement/service-fee invoice directly to the school/employer. The Candidate authorizes the Agency to share reasonable placement, fee, invoice and reconciliation information with the employer for billing, payment and reconciliation purposes.</p>

    <div class="section-header">18. NO DOUBLE RECOVERY</div>
    <p>The Agency shall not seek duplicate recovery of the same placement/service fee from both Candidate and employer where the employer has already paid the full applicable fee for that placement. Where an employer pays only part of an amount that remains payable under a written arrangement, the remaining amount may be addressed according to that arrangement.</p>

    <div class="section-header">19. TAXES, STATUTORY DEDUCTIONS AND INVOICING</div>
    <p>Any applicable GST, tax, statutory deduction or invoicing requirement shall be handled in accordance with applicable law. The Agency may issue receipts, invoices, payment requests and related digital records as applicable.</p>

    <div class="section-header">20. JOINING COMMITMENT</div>
    <p>Where the Candidate accepts a placement and confirms a joining date, the Candidate is expected to make a genuine effort to join on the agreed date and complete reasonable joining formalities. Any genuine difficulty should be communicated to the Agency as soon as reasonably practicable.</p>

    <div class="section-header">21. WITHDRAWAL NOTICE &mdash; FIVE CALENDAR DAYS</div>
    <p>If the Candidate decides not to join after accepting a placement, the Candidate should inform the Agency as early as reasonably possible and, where reasonably practicable, at least 5 calendar days before the agreed joining date.</p>

    <div class="section-header">22. LAST-MINUTE WITHDRAWAL / NO-SHOW</div>
    <p>A deliberate last-minute withdrawal or no-show without a genuine substantial reason after confirmed acceptance and joining may constitute a material breach. The Agency may seek contractual compensation of up to &#8377;5,000, subject to applicable law. This amount is contractual compensation and is not intended to operate as a criminal fine or permit unlawful recovery.</p>

    <div class="section-header">23. 30-DAY CONTINUITY EXPECTATION</div>
    <p>After joining through the Agency, the Candidate is expected to make a genuine effort to continue for at least 30 days, subject to the employment agreement and applicable law. Leaving after one or a few days does not automatically cancel or extinguish an applicable Service Fee. Genuine substantial circumstances may include non-payment of salary, material unauthorized change in agreed terms, serious employer misrepresentation, unsafe/unlawful conditions, serious medical or family emergency, or another substantial lawful circumstance.</p>

    <div class="section-header">23.1 CANDIDATE DISCONTINUATION / TERMINATION AFTER JOINING</div>
    <p>If, after joining an opportunity facilitated by the Agency, the Candidate is terminated, dismissed, released, discontinued by the employer, resigns voluntarily, abandons the position, or otherwise discontinues the employment for any reason attributable to the Candidate or the employment relationship, a discontinuation service charge equal to 30% of the Candidate’s first-month gross salary/remuneration shall become applicable, provided that the Agency has first completed a reasonable verification with the school/employer regarding the reason and circumstances of discontinuation. No discontinuation charge shall be treated as finally due on the basis of an unverified allegation alone. Where the Agency’s verification establishes that the discontinuation falls within this clause, the applicable charge shall be payable notwithstanding any separate dispute between the Candidate and the school/employer regarding salary, reimbursement or other employment dues. Non-payment or withholding of salary by the school/employer does not, by itself, extinguish a separately applicable contractual service fee owed to the Agency, subject to the specific placement record and applicable law. The Agency shall not seek duplicate recovery of the same placement/service fee where the full applicable fee has already been paid by the employer.</p>

    <div class="section-header">23.2 VERIFICATION RECORD</div>
    <p>For purposes of the discontinuation charge, the Agency may obtain confirmation from the concerned school/employer by email, WhatsApp, telephone followed by written confirmation, HR/admin record, termination/release communication, attendance/joining record or another reasonably reliable source. If the Agency cannot reasonably verify the discontinuation event or its relevant circumstances, the 30% discontinuation charge shall not be treated as finally established solely on an unverified assertion.</p>

    <div class="section-header">24. NO EMPLOYMENT GUARANTEE; NO AUTOMATIC FEE WAIVER</div>
    <p>A recruitment opportunity or selection process may end without employment for reasons outside the Agency’s control. Unless a written placement record provides otherwise, the Candidate’s applicable payment obligations are governed by this Agreement and the relevant placement record. Nothing in this clause creates a guarantee of employment.</p>

    <div class="major-section-banner">CANDIDATE OBLIGATIONS, DATA &amp; DIGITAL EXECUTION</div>

    <div class="section-header">25. CANDIDATE INFORMATION AND DOCUMENTS</div>
    <p>The Candidate shall provide genuine, accurate and complete personal, educational, professional and employment information and authentic supporting documents. This includes CV/resume, educational qualifications, experience details, experience certificates, salary information, identity documents, references, professional certifications and other recruitment-related information reasonably requested.</p>

    <div class="section-header">25.1. DUTY TO UPDATE</div>
    <p>The Candidate shall promptly notify the Agency of any material change affecting the accuracy of information previously provided, including employment status, salary, availability, location preference, contact information or joining status.</p>

    <div class="section-header">26. VERIFICATION AND MISREPRESENTATION</div>
    <p>The Agency may conduct reasonable verification of information or documents supplied by the Candidate. Forged, fraudulent, materially misleading or intentionally concealed information may result in suspension or termination of processing and may expose the Candidate to contractual or legal consequences where applicable.</p>

    <div class="section-header">27. CANDIDATE PROFESSIONAL CONDUCT</div>
    <p>The Candidate shall communicate respectfully and professionally with the Agency and prospective employers; attend confirmed interviews, tests and demonstrations; communicate acceptance, rejection, withdrawal and joining status; and avoid conduct intended to improperly damage the Agency–employer relationship.</p>

    <div class="section-header">27.1 JOINING / EMPLOYMENT STATUS UPDATES</div>
    <p>The Candidate shall promptly inform the Agency of interview outcomes, offer acceptance, joining, non-joining, resignation, termination, discontinuation, salary receipt and any material change relevant to a placement facilitated by the Agency. Failure to provide material updates or deliberate concealment of a placement or salary receipt may be considered in determining contractual recovery, subject always to applicable law.</p>

    <div class="section-header">28. NON-CIRCUMVENTION</div>
    <p>Where an employment opportunity has been introduced, sourced, submitted, coordinated or materially facilitated by the Agency, the Candidate shall not intentionally bypass the Agency for the purpose of avoiding an applicable Service Fee. This clause is limited to Agency-introduced or Agency-facilitated opportunities and does not restrict unrelated independent job applications.</p>

    <div class="section-header">29. DATA PROCESSING AND PRIVACY</div>
    <p>The Candidate consents, to the extent permitted and required by applicable law, to the Agency collecting, storing, using and sharing relevant personal and professional information for registration, profile verification, vacancy matching, application submission, interview coordination, employer communication, placement tracking, joining administration, invoicing/payment administration, record keeping, legal compliance and dispute management. The Agency shall apply appropriate safeguards and process data in accordance with applicable law as provisions come into force.</p>

    <div class="section-header">29.1. DATA MINIMIZATION AND PURPOSE</div>
    <p>The Agency should seek to process information reasonably necessary for the stated recruitment and administrative purposes. Where applicable law provides rights concerning access, correction, withdrawal of consent, grievance handling or other data-principal rights, those rights shall be handled in accordance with the law in force at the relevant time.</p>

    <div class="section-header">30. LIVE PHOTOGRAPH</div>
    <p>The Candidate may be required to provide or capture a current/live photograph through the digital registration process for identity/profile verification and recruitment records. Where reasonably necessary for recruitment, it may be shared with prospective employers.</p>

    <div class="section-header">31. LIVE LOCATION</div>
    <p>Where the digital process requires location verification, the Candidate may be asked to provide current/live location information for legitimate purposes such as registration verification, attendance, joining coordination, fraud prevention or administration. Location information should be handled in accordance with applicable law and the stated purpose.</p>

    <div class="section-header">32. ELECTRONIC SIGNATURE AND ONLINE ACCEPTANCE</div>
    <p>This Agreement is intended to be executed entirely online. Electronic signature, digital signature, typed name, uploaded signature, consent checkbox or another enabled electronic authentication may constitute acceptance, subject to applicable law and the Agency’s digital system. The use of electronic form shall not, by itself, make the Agreement unenforceable where electronic contracting is legally recognized.</p>

    <div class="section-header">33. AGENCY AUTHENTICATION</div>
    <p>The Agency may apply its authorized digital signature, official digital stamp/seal and authorized-signatory information. Authorized Signatory: Aditya Rajveer &mdash; Founder &amp; CEO, Vedanta Placement Agency.</p>

    <div class="section-header">34. DIGITAL AGREEMENT RECORD AND AUDIT TRAIL</div>
    <p>After final online acceptance, the Agency may maintain the completed Agreement as a single electronic record containing Candidate ID, Agreement ID, Candidate acceptance/signature, Agency authentication, acceptance date/time, consent records, IP/device/system information where lawfully collected, and relevant audit information. The completed record may be delivered through the portal/dashboard, email, WhatsApp or another electronic channel.</p>

    <div class="section-header">34.1 DIGITAL PAYMENT AND COMMUNICATION RECORDS</div>
    <p>Payment confirmations, invoices, receipts, payment links, bank/UPI records, portal records, emails, WhatsApp messages, interview records, offer/joining records and other authenticated digital records may be maintained as part of the recruitment and placement audit trail, subject to applicable law.</p>

    <div class="section-header">35. ELECTRONIC RECORDS AND EVIDENCE</div>
    <p>The parties intend that electronically stored records, electronic communications, electronic signatures/acceptances and system-generated records may be retained and produced as evidence where relevant and legally admissible. Nothing in this clause creates a presumption beyond what applicable evidence law provides.</p>

    <div class="section-header">36. CONFIDENTIALITY</div>
    <p>The Candidate shall not intentionally misuse or disclose non-public recruitment information, employer information, commercial terms, internal processes, candidate information or confidential communications received through the Agency, except where disclosure is required by law or reasonably necessary for legitimate recruitment.</p>

    <div class="section-header">37. PROFESSIONAL CONDUCT AND ANTI-FRAUD</div>
    <p>The Candidate shall not engage in threats, abuse, harassment, intimidation, impersonation, forged documents, deliberate false representation, fraud, unlawful inducement or intentional misuse of recruitment information. Where conduct may constitute an offence, the Agency may take action available under applicable law.</p>

    <div class="section-header">38. INTELLECTUAL PROPERTY AND MATERIALS</div>
    <p>Unless otherwise agreed, Agency-created forms, branding, website content, recruitment templates, databases, internal systems and other proprietary materials remain the Agency’s property or that of its licensors. The Candidate receives only the limited right to use materials supplied for the recruitment process.</p>

    <div class="section-header">39. NO ASSIGNMENT BY CANDIDATE</div>
    <p>The Candidate may not assign or transfer this Agreement or the Candidate’s obligations to another person without the Agency’s written/digital consent. The Agency may use authorized personnel, technology providers or service providers to perform administrative functions, subject to applicable law and confidentiality/data requirements.</p>

    <div class="major-section-banner">BREACH, LEGAL FRAMEWORK, DISPUTES &amp; GENERAL PROVISIONS</div>
    <p style="text-align: center; font-size: 6.8pt; color: #4b5563; margin-bottom: 3px;">Contractual remedies and governing provisions</p>

    <div class="section-header">40. BREACH AND NOTICE PROCESS</div>
    <p>If the Agency reasonably believes that a contractual amount is due or a material breach has occurred, it may issue a digital reminder or formal notice describing the issue and, where appropriate, provide a reasonable opportunity for clarification or payment. The Candidate may respond through the designated digital communication channel.</p>

    <div class="section-header">40.1 OPPORTUNITY TO CLARIFY</div>
    <p>Before treating a disputed monetary obligation as finally established, the Agency may provide a reasonable opportunity for the Candidate to submit relevant documents or clarification. This does not prevent the Agency from issuing reminders or taking lawful steps to preserve or recover a valid contractual claim.</p>

    <div class="section-header">41. CONTRACTUAL REMEDIES</div>
    <p>Subject to applicable law, the Agency may pursue valid outstanding contractual amounts and reasonable documented losses, costs or compensation that are legally recoverable. The parties acknowledge that compensation for breach is governed by applicable law and is not automatically equal to any amount stated in a contract.</p>

    <div class="section-header">42. STIPULATED COMPENSATION / &#8377;5,000 PROVISION</div>
    <p>Any stipulated amount, including the &#8377;5,000 provision for an unjustified last-minute withdrawal/no-show, is subject to the applicable legal framework governing contractual compensation and penalties. The legally recoverable amount shall not exceed what applicable law permits. This clause shall not be interpreted as authorizing an unlawful penalty.</p>

    <div class="section-header">43. INDEMNITY &mdash; LIMITED AND LAWFUL</div>
    <p>To the extent permitted by law, the Candidate shall be responsible for direct losses or liabilities caused by the Candidate’s fraud, intentional misrepresentation, forged documents, unlawful conduct or deliberate breach of this Agreement. No indemnity shall extend to losses that cannot lawfully be shifted to the Candidate or to liabilities caused by the Agency’s own non-excludable legal obligations.</p>

    <div class="section-header">44. LIMITATION OF AGENCY RESPONSIBILITY</div>
    <p>To the extent permitted by law, the Agency shall not be treated as the employer, salary guarantor, security provider or insurer of the Candidate. The Agency’s responsibility is primarily recruitment and placement facilitation and any additional responsibility expressly undertaken in writing. Nothing in this Agreement excludes liability that cannot lawfully be excluded, including liability arising from the Agency’s own non-excludable obligations.</p>

    <div class="section-header">45. FORCE MAJEURE / EVENTS BEYOND CONTROL</div>
    <p>Neither party shall be treated as in breach solely because performance is prevented or materially delayed by circumstances beyond reasonable control, including natural disasters, war, civil disturbance, government restrictions, major technology failures, widespread communication outages or other comparable events, provided the affected party acts reasonably to mitigate the impact and communicates where practicable.</p>

    <div class="section-header">46. NOTICES AND OFFICIAL COMMUNICATION</div>
    <p>Notices may be delivered through registered email, WhatsApp, SMS, Agency dashboard/portal, telephone followed by electronic confirmation, or other digital contact details supplied by the Candidate. The Candidate is responsible for keeping contact details current and accessible.</p>

    <div class="section-header">47. GOVERNING LAW AND APPLICABLE LEGAL FRAMEWORK</div>
    <p>This Agreement shall be governed by the laws of India. Its interpretation and enforcement are subject to applicable provisions of Indian contract law, electronic transactions/electronic records law, evidence law, data-protection/privacy law, consumer law where applicable, and applicable employment/labour laws governing matters between the Candidate and employer. The applicable legal framework may change, and mandatory provisions in force at the relevant time shall prevail.</p>

    <div class="section-header">48. ELECTRONIC CONTRACTING &mdash; STATUTORY RECOGNITION</div>
    <p>The parties acknowledge that Indian law recognizes contracts formed through electronic means and does not treat a contract as unenforceable solely because electronic records or electronic means were used, subject to the requirements and exclusions of applicable law.</p>

    <div class="section-header">49. EVIDENCE AND ELECTRONIC RECORDS</div>
    <p>The parties may rely on the Agreement, electronic records, electronic communications, acceptance records, digital signatures and system logs to the extent admissible under the Bharatiya Sakshya Adhiniyam, 2023 and other applicable evidence law. The Agency shall preserve relevant records in accordance with its retention practices and applicable law.</p>

    <div class="section-header">50. DATA-PROTECTION COMMENCEMENT AND APPLICABILITY</div>
    <p>The Digital Personal Data Protection Act, 2023 has phased commencement under the Government’s notification. Accordingly, the parties shall comply with the provisions of that Act and applicable rules when and to the extent they are in force, together with any other applicable privacy/data-protection requirements in force at the relevant time.</p>

    <div class="section-header">51. MANDATORY RIGHTS AND CONSUMER PROTECTION</div>
    <p>Nothing in this Agreement is intended to waive or restrict a statutory right, mandatory consumer protection, employment protection, data-protection right or other legal remedy that cannot lawfully be waived. Where a mandatory law provides a different forum, procedure or remedy, that mandatory requirement shall prevail.</p>

    <div class="section-header">51.1 APPLICABLE LAW; REASONABLE COMPENSATION; MANDATORY LIMITATIONS</div>
    <p>All fees, late charges, interest, discontinuation charges, contractual compensation and other monetary remedies under this Agreement are subject to applicable Indian law and shall operate only to the extent legally enforceable. Contractual stipulations concerning compensation or penalty shall be subject to the principles governing compensation for breach of contract, including Section 74 of the Indian Contract Act, 1872, and any other applicable law. Nothing in this Agreement authorizes an unlawful penalty, unreasonable recovery, coercive recovery method, waiver of a mandatory consumer or statutory right, or exclusion of liability that cannot lawfully be excluded. If any monetary provision is held excessive, unreasonable or otherwise unenforceable, it shall apply only to the maximum extent permitted by law.</p>

    <div class="section-header">52. STAMP DUTY, REGISTRATION AND EXECUTION FORMALITIES</div>
    <p>The parties shall comply with any stamp duty, registration, execution, notarisation or evidentiary formality that is legally applicable to this Agreement or to a particular transaction. Unless mandatory law requires otherwise, any applicable stamp duty or transaction-specific execution cost shall be borne by the party legally responsible for that cost or as separately agreed in writing. The electronic form of this Agreement does not by itself determine whether any separate statutory duty or formality applies.</p>

    <div class="section-header">53. JURISDICTION</div>
    <p>Subject to mandatory applicable law, statutory forums, consumer jurisdiction and any other forum or remedy that cannot lawfully be excluded, disputes arising from this Agreement shall be subject to the jurisdiction of the competent courts/authorities at Patna, Bihar, India. Nothing in this clause is intended to restrict a party from approaching a court, commission, statutory authority, regulator or other forum where such jurisdiction is mandatorily available under applicable law.</p>

    <div class="section-header">54. DISPUTE RESOLUTION AND PRE-LITIGATION COMMUNICATION</div>
    <p>Before commencing formal proceedings, the parties should, where reasonably practicable, attempt to resolve the issue through written/digital communication and a reasonable opportunity for clarification. Nothing in this clause prevents a party from approaching a competent court, statutory authority, regulator or other forum where immediate or mandatory legal relief is available.</p>

    <div class="section-header">55. SCHOOL&ndash;CANDIDATE DISPUTES</div>
    <p>Employment disputes concerning salary, duties, workplace conditions, leave, accommodation, termination or other employment obligations should ordinarily be addressed between Candidate and school/employer according to their employment arrangement and applicable law. The Agency may facilitate communication but does not automatically become a party to every employment dispute.</p>

    <div class="section-header">55.1 PERSONAL SAFETY, SERIOUS INCIDENTS, INJURY OR DEATH</div>
    <p>The Agency acts solely as a recruitment and placement facilitator and is not the Candidate’s employer, workplace operator, accommodation provider, transport provider, medical provider, security provider or insurer. To the extent permitted by applicable law, the Agency shall not be responsible for any accident, bodily injury, illness, disability, death, suicide, attempted suicide, assault, theft, loss or damage to personal property, travel incident, workplace incident, accommodation incident, misconduct of any third party, or other physical, financial or personal loss occurring after introduction, selection, joining or travel, where such event is not caused by the Agency’s own proven act or omission. Responsibility for workplace safety, employment duties, accommodation, food, transport arranged by the employer and other employer-controlled facilities ordinarily remains with the concerned employer/provider. Nothing in this clause excludes liability that cannot lawfully be excluded, including liability arising from the Agency’s own non-excludable legal obligations or proven wrongful act or omission.</p>

    <div class="section-header">55.2 EMERGENCY / SERIOUS INCIDENT COOPERATION</div>
    <p>In the event of a serious accident, medical emergency, disappearance, death or other major incident connected with an employment placement, the Agency may, where reasonably practicable and legally permitted, assist with communication between the Candidate, family, school/employer or appropriate authority and may provide relevant placement records. Such assistance is administrative in nature and does not make the Agency the employer, insurer, medical provider, security provider or guarantor of the Candidate’s safety or outcome.</p>

    <div class="section-header">56. AGENCY RIGHT TO UPDATE FUTURE TERMS</div>
    <p>Vedanta Placement Agency may update, modify or replace recruitment, registration, placement, service-fee, digital-process and administrative terms for future transactions, subject to applicable law. Material revised terms may be communicated digitally and, where required, may require fresh acceptance. No retrospective change shall alter accrued rights or obligations unless legally permitted or expressly agreed.</p>

    <div class="section-header">57. AMENDMENT / VARIATION OF THIS AGREEMENT</div>
    <p>Any amendment specific to the Candidate shall be effective only when recorded in a written or authenticated digital record and accepted where required. Operational communications shall not automatically amend this Agreement unless they expressly state that they are intended to amend or supplement it.</p>

    <div class="section-header">58. SEVERABILITY</div>
    <p>If any provision is held invalid, unlawful or unenforceable, the remaining provisions shall continue to operate to the extent permitted by law. The affected provision shall be interpreted or modified only to the extent legally permitted.</p>

    <div class="section-header">59. NO WAIVER</div>
    <p>Failure or delay by the Agency in enforcing any provision shall not automatically constitute a waiver of that provision or any other right unless expressly recorded or confirmed.</p>

    <div class="section-header">60. ENTIRE AGREEMENT AND ORDER OF PRECEDENCE</div>
    <p>This Agreement, together with applicable vacancy/application records, written fee confirmations, placement records, invoices, offer/joining records and expressly incorporated digital communications, forms the contractual framework governing the Candidate’s relationship with the Agency. If a specific written or authenticated digital placement record expressly conflicts with a general provision of this Agreement for that particular placement, the specific record shall control only to the extent of the stated conflict. Any waiver, modification or special commercial term must be recorded in writing or in an authenticated digital record.</p>

    <div class="section-header">61. NO PARTNERSHIP OR AGENCY BETWEEN THE PARTIES</div>
    <p>Nothing in this Agreement creates a partnership, joint venture, employment relationship, fiduciary relationship or agency relationship between the Candidate and the Agency beyond the recruitment/placement services expressly described.</p>

    <div class="section-header">62. THIRD-PARTY RIGHTS</div>
    <p>Unless expressly stated otherwise or required by applicable law, a person who is not a party to this Agreement shall not acquire contractual rights merely because that person benefits from a provision.</p>

    <div class="section-header">63. SURVIVAL</div>
    <p>Payment obligations, confidentiality, non-circumvention, data processing, electronic records, dispute handling, indemnity to the extent lawful and other provisions that by their nature are intended to continue shall survive completion or termination to the extent permitted by law.</p>

    <div class="section-header">64. COUNTERPARTS AND DIGITAL COPIES</div>
    <p>The Agreement may be accepted through electronic workflows and stored in one or more electronic copies. Each authenticated digital copy may form part of the same Agreement record. Physical counterparts are not required for the Agency’s standard digital execution process.</p>

    <div class="section-header">65. LANGUAGE AND INTERPRETATION</div>
    <p>This Agreement is drafted in English. Where an explanatory translation is provided, the English version shall govern to the extent permitted by law unless the parties expressly agree otherwise in a legally valid record.</p>

    <div class="page-break"></div>

    {{-- ============================== FINAL SIGNATURE & DECLARATION PAGE ============================== --}}
    <div class="major-section-banner">FINAL CANDIDATE DECLARATION &amp; DIGITAL EXECUTION</div>
    <p style="text-align: center; font-size: 6.8pt; color: #4b5563; margin-bottom: 4px;">Candidate confirmation and authenticated acceptance</p>

    <div class="section-header">66. FINAL CANDIDATE DECLARATION</div>
    <p>By completing the final online acceptance, the Candidate confirms that the Candidate has had an opportunity to read and understand this Agreement and voluntarily accepts its terms. The Candidate confirms that the Candidate understands the registration fee structure, placement/service fees, payment timing, employer direct invoicing/no-double-recovery arrangement, joining commitment, five-day withdrawal notice, &#8377;5,000 contractual compensation provision, 30-day continuity expectation, non-circumvention obligation, data-processing terms, live photograph/location requirements where applicable, electronic execution and the Agency’s limited recruitment/placement role.</p>
    <ul style="margin: 3px 0 5px 14px; padding: 0; font-size: 6.9pt; line-height: 1.26;">
        <li>All information and documents submitted by me are genuine and accurate to the best of my knowledge.</li>
        <li>I understand that registration and recruitment processing do not themselves guarantee employment.</li>
        <li>I understand that the final employment decision is made by the concerned school/employer.</li>
        <li>I understand that the employer is ordinarily responsible for salary and employment obligations.</li>
        <li>I understand the applicable registration and placement/service-fee obligations.</li>
        <li>I understand the joining, withdrawal and continuity provisions.</li>
        <li>I consent to relevant recruitment-related processing of my personal/professional information subject to applicable law.</li>
        <li>I consent to the use of electronic records and electronic acceptance for this Agreement.</li>
        <li>I understand that mandatory rights and legal protections cannot be waived by this Agreement.</li>
    </ul>

    <div class="section-header">67. DIGITAL ACCEPTANCE RECORD</div>
    <table class="info-table" style="margin-bottom: 3px;">
        <tr>
            <th>Candidate Name</th>
            <td><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <th>Candidate ID</th>
            <td><strong>{{ $candidateId }}</strong></td>
        </tr>
        <tr>
            <th>Agreement ID</th>
            <td><strong>{{ $agreementId }}</strong></td>
        </tr>
        <tr>
            <th>Registered Mobile</th>
            <td>{{ $user->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Registered Email</th>
            <td>{{ $user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Acceptance Date &amp; Time</th>
            <td>{{ $agreementDateTime }}</td>
        </tr>
        <tr>
            <th>IP / Device / Audit Reference</th>
            <td>{{ $profile->signature_ip_address ?? 'Captured Online' }} / {{ Str::limit($profile->signature_device_info ?? 'System Captured', 50) }}</td>
        </tr>
        <tr>
            <th>Execution Status</th>
            <td><strong style="color: #15803d;">ACCEPTED ONLINE / DIGITALLY EXECUTED</strong></td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <th>CANDIDATE DIGITAL SIGNATURE</th>
            <th>AGENCY AUTHORIZED SIGNATORY</th>
        </tr>
        <tr>
            <td class="signature-box">
                <div style="font-size: 5.8pt; color: #6b7280; margin-bottom: 2px;">[Electronic signature / typed full name captured online]</div>
                <div style="font-size: 6.2pt; color: #374151; margin-bottom: 3px;">
                    Candidate Name: <strong>{{ $user->name }}</strong><br>
                    Date/Time: {{ $agreementDateTime }}
                </div>
                
                <div style="text-align: center; min-height: 38px; padding-top: 2px;">
                    @if(isset($signature_type) && $signature_type === 'type')
                        <div class="cursive-signature" style="color: #111827; font-size: 18px;">{{ $signature }}</div>
                    @elseif(!empty($signature))
                        <img src="{{ $signature }}" style="max-height: 38px; max-width: 150px;" alt="Candidate Signature">
                    @else
                        <div class="cursive-signature" style="color: #111827; font-size: 18px;">{{ $user->name }}</div>
                    @endif
                </div>

                <div style="font-size: 5.8pt; color: #6b7280; text-align: center; margin-top: 2px;">{{ $user->name }} &bull; Digital Signature</div>
            </td>
            <td class="signature-box">
                <div style="text-align: center; min-height: 38px; padding-top: 1px;">
                    @if(!empty($adminSignBase64))
                        <img src="{{ $adminSignBase64 }}" style="max-height: 38px; max-width: 170px; height: auto; width: auto; display: inline-block; vertical-align: middle;" alt="Aditya Rajveer Signature">
                    @else
                        <div class="cursive-signature" style="font-size: 22px; color: #004d99; margin-bottom: 1px;">
                            Aditya Rajveer
                        </div>
                    @endif
                </div>
                <div style="font-size: 6.5pt; font-weight: bold; color: #111827; text-align: center;">Digital Signature &mdash; Aditya Rajveer</div>
                <div style="font-size: 6.2pt; color: #374151; margin-top: 3px; line-height: 1.2;">
                    <strong>Aditya Rajveer</strong><br>
                    Founder &amp; CEO<br>
                    Vedanta Placement Agency<br>
                    <span style="color: #15803d; font-weight: bold; font-size: 6pt;">Digital authorization applied</span>
                </div>
            </td>
        </tr>
        <tr>
            <td style="font-size: 6pt; color: #374151; border-top: none; padding-top: 1px;">
                Candidate ID: <strong>{{ $candidateId }}</strong>
            </td>
            <td style="font-size: 6pt; color: #374151; border-top: none; padding-top: 1px; text-align: right;">
                Agreement ID: <strong>{{ $agreementId }}</strong>
            </td>
        </tr>
    </table>

    <div class="green-verification-box">
        <div style="color: #15803d; font-weight: bold; font-size: 7.2pt;">&#10003; DIGITALLY SIGNED &amp; VERIFIED</div>
        <div style="font-size: 6.5pt; color: #166534; margin-top: 2px; line-height: 1.25;">
            Signed By: <strong>{{ $user->name }}</strong><br>
            Date: {{ $agreementDateTime }}<br>
            Candidate ID: [{{ $candidateId }}]<br>
            Agreement ID: [{{ $agreementId }}]
        </div>
    </div>

    <div style="font-size: 6pt; font-weight: bold; color: #374151; text-align: center; margin-top: 4px;">
        ONLINE / DIGITAL EXECUTION ONLY &mdash; No physical signature, physical stamp, printed submission or offline approval is required for the Agency&rsquo;s standard digital execution workflow.
    </div>

    <div style="font-size: 5.6pt; color: #6b7280; text-align: justify; margin-top: 4px; line-height: 1.2;">
        <strong>DRAFTING / LEGAL REVIEW NOTE</strong> &mdash; This Agreement is a comprehensive commercial/legal template for Indian use. All registration fees, Service Fees, late charges, interest, discontinuation charges and contractual compensation are subject to applicable law and the facts of the relevant placement. Nothing in this Agreement is intended to authorize an unlawful penalty, coercive recovery, waiver of mandatory statutory rights, or exclusion of liability that cannot legally be excluded. The Agreement should be reviewed by a qualified advocate before operational deployment and adapted for transaction-specific facts, tax/GST requirements, state-specific requirements and any changes in applicable law.
    </div>

</body>
</html>
