<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Institution Recruitment Agreement - {{ $employerCode }}</title>
    <style>
        @page {
            margin: 52px 38px 50px 38px;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8.2pt;
            line-height: 1.45;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header-section {
            text-align: center;
            border-bottom: 2px solid #0b6e87;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .logo-img {
            height: 44px;
            width: auto;
            margin-bottom: 4px;
        }
        .main-title {
            font-size: 14pt;
            font-weight: bold;
            color: #031b4e;
            margin: 0 0 2px 0;
        }
        .sub-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0b6e87;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }
        .sub-desc {
            font-size: 7.6pt;
            color: #4b5563;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .info-table th,
        .info-table td {
            border: 1px solid #d1d5db;
            padding: 5px 8px;
            text-align: left;
            font-size: 7.8pt;
            vertical-align: top;
        }
        .info-table th {
            width: 28%;
            background-color: #f3f4f6;
            color: #031b4e;
            font-weight: bold;
        }
        h3 {
            font-size: 8.8pt;
            color: #031b4e;
            margin: 10px 0 4px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 2px;
        }
        p, li {
            margin: 0 0 5px 0;
            text-align: justify;
        }
        ul {
            margin: 3px 0 8px 18px;
            padding: 0;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            page-break-inside: avoid;
        }
        .sig-table th {
            background-color: #031b4e;
            color: #ffffff;
            padding: 6px 8px;
            font-size: 7.6pt;
            border: 1px solid #031b4e;
        }
        .sig-box {
            width: 50%;
            border: 1px solid #d1d5db;
            padding: 10px;
            vertical-align: top;
            height: 110px;
        }
        .footer {
            position: fixed;
            bottom: -32px;
            left: 0;
            right: 0;
            height: 22px;
            border-top: 1px solid #d1d5db;
            padding-top: 4px;
            font-size: 6.5pt;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="footer">
        Vedanta Placement Agency® — Institution Service Agreement | Ref: {{ $employerCode }} | Download, sign/stamp &amp; upload on your Employer Dashboard
    </div>

    <div class="header-section">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo-img" alt="Vedanta Logo"><br>
        @endif
        <h1 class="main-title">VEDANTA PLACEMENT AGENCY®</h1>
        <div class="sub-title">SCHOOL / INSTITUTION RECRUITMENT &amp; PLACEMENT AGREEMENT</div>
        <div class="sub-desc">Standard Onboarding Terms for Educational Institutions &amp; Employers</div>
    </div>

    <table class="info-table">
        <tr>
            <th>AGENCY</th>
            <td>Vedanta Placement Agency® (Patna, Bihar, India — info@vedantaplacementagency.in)</td>
        </tr>
        <tr>
            <th>INSTITUTION REF ID</th>
            <td><strong>{{ $employerCode }}</strong></td>
        </tr>
        <tr>
            <th>SCHOOL / INSTITUTION NAME</th>
            <td><strong>{{ $schoolName }}</strong></td>
        </tr>
        <tr>
            <th>PRIMARY CONTACT PERSON</th>
            <td>{{ $contactPerson }}</td>
        </tr>
        <tr>
            <th>REGISTERED EMAIL / PHONE</th>
            <td>{{ $employer->email }} &bull; {{ $employer->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>INSTITUTION ADDRESS</th>
            <td>{{ $profile->address ?: 'To be confirmed by Institution' }}</td>
        </tr>
        <tr>
            <th>DOCUMENT GENERATED ON</th>
            <td>{{ $generatedDateIst }}</td>
        </tr>
    </table>

    <h3>1. SCOPE OF RECRUITMENT FACILITATION</h3>
    <p>Vedanta Placement Agency® (&ldquo;Agency&rdquo;) acts as a recruitment and placement facilitator connecting verified educators and academic staff with {{ $schoolName }} (&ldquo;Institution&rdquo;). The Agency introduces screened candidates and coordinates interviews based on vacancy requirements submitted through the Agency portal or CRM.</p>

    <h3>2. GENUINE VACANCIES &amp; TRANSPARENT EMPLOYMENT TERMS</h3>
    <ul>
        <li>The Institution confirms that all job postings submitted carry genuine open vacancies with accurate salary ranges, qualification criteria, campus facilities, and work expectations.</li>
        <li>All salary payments, workplace safety, accommodation (where offered), and statutory employment obligations remain solely between the Institution and the hired educator.</li>
    </ul>

    <h3>3. CANDIDATE SELECTION, JOINING UPDATES &amp; NON-CIRCUMVENTION</h3>
    <ul>
        <li>The Institution agrees to promptly notify the Agency regarding interview shortlisting, job offers issued, candidate joining dates, non-joining, or early discontinuation for candidates introduced by the Agency.</li>
        <li>Where an educator profile is introduced or coordinated by the Agency, the Institution shall not intentionally bypass the Agency for the purpose of avoiding agreed coordination terms.</li>
    </ul>

    <h3>4. CONFIDENTIALITY &amp; DATA PROTECTION</h3>
    <p>Candidate resumes, identity photographs, contact information, and commercial terms shared through Vedanta Placement Agency® are strictly confidential and shall be used solely for legitimate recruitment evaluation in accordance with applicable Indian data-protection laws.</p>

    <h3>5. EXECUTION, VERIFICATION &amp; GOVERNING LAW</h3>
    <p>The Institution shall sign and/or stamp this Agreement and upload the completed copy on the Employer Dashboard for verification by the Agency Administrator. Subject to mandatory provisions of applicable law, this Agreement is governed by the laws of India with jurisdiction at Patna, Bihar.</p>

    <table class="sig-table">
        <tr>
            <th>FOR VEDANTA PLACEMENT AGENCY®</th>
            <th>FOR {{ strtoupper($schoolName) }}</th>
        </tr>
        <tr>
            <td class="sig-box">
                <div style="text-align: center; min-height: 44px;">
                    @if(!empty($adminSignBase64))
                        <img src="{{ $adminSignBase64 }}" style="max-height: 40px; max-width: 170px;" alt="Authorized Signature">
                    @else
                        <div style="font-style: italic; font-weight: bold; font-size: 16pt; color: #031b4e;">Aditya Rajveer</div>
                    @endif
                </div>
                <div style="font-size: 7.4pt; color: #111827; margin-top: 6px; line-height: 1.35;">
                    <strong>Aditya Rajveer</strong><br>
                    Founder &amp; CEO, Vedanta Placement Agency®<br>
                    Authorized Signatory
                </div>
            </td>
            <td class="sig-box">
                <div style="height: 52px; border-bottom: 1px dashed #9ca3af; margin-bottom: 6px;"></div>
                <div style="font-size: 7.4pt; color: #374151; line-height: 1.35;">
                    <strong>Authorized Signatory Signature &amp; Institution Seal</strong><br>
                    Name: {{ $contactPerson }}<br>
                    Designation: _______________________<br>
                    Date: _____________________________
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
