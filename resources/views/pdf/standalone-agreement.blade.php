<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Agreement - {{ $agreement->agreement_code }}</title>
    <style>
        @page {
            margin: 56px 36px 52px 36px;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 7.4pt;
            line-height: 1.38;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .watermark {
            position: fixed;
            top: 32%;
            left: 18%;
            width: 64%;
            text-align: center;
            opacity: 0.05;
            z-index: -1000;
        }
        .watermark img {
            width: 340px;
            height: auto;
        }
        .footer {
            position: fixed;
            bottom: -34px;
            left: 0;
            right: 0;
            height: 24px;
            border-top: 1px solid #d1d5db;
            padding-top: 4px;
            font-size: 6.2pt;
            color: #6b7280;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-left {
            text-align: left;
            font-weight: bold;
            color: #15803d;
        }
        .footer-right {
            text-align: right;
        }
        .page-num:before {
            content: "Page " counter(page);
        }
        .header-section {
            text-align: center;
            border-bottom: 2px solid #0b6e87;
            padding-bottom: 10px;
            margin-bottom: 12px;
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
            letter-spacing: 0.4px;
        }
        .sub-service {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0b6e87;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .sub-desc {
            font-size: 7.5pt;
            color: #4b5563;
            font-weight: bold;
        }
        .sub-intent {
            font-size: 6.8pt;
            color: #6b7280;
            margin-top: 3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .info-table th,
        .info-table td {
            border: 1px solid #d1d5db;
            padding: 4px 7px;
            text-align: left;
            font-size: 7.2pt;
            vertical-align: top;
        }
        .info-table th {
            width: 30%;
            background-color: #f3f4f6;
            color: #031b4e;
            font-weight: bold;
        }
        .info-table td {
            color: #111827;
        }
        /* Normalize Tailwind utility classes used inside agreement-text partial for DomPDF */
        .space-y-4 {
            font-size: 7.1pt;
            line-height: 1.36;
            color: #374151;
        }
        .text-text-main {
            color: #111827 !important;
        }
        .text-text-muted {
            color: #374151 !important;
        }
        .text-brand-teal {
            color: #0b6e87 !important;
        }
        .bg-gray-50 {
            background-color: #f3f4f6 !important;
        }
        .font-bold,
        .font-extrabold {
            font-weight: bold;
        }
        .uppercase {
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .text-sm {
            font-size: 7.6pt;
        }
        .border-t {
            border-top: 1px solid #d1d5db;
        }
        .border-b {
            border-bottom: 1px solid #d1d5db;
        }
        .border-gray-200 {
            border-color: #e5e7eb;
        }
        .py-1\.5 {
            padding-top: 4px;
            padding-bottom: 4px;
        }
        .mt-4 {
            margin-top: 7px;
        }
        .mt-6 {
            margin-top: 10px;
        }
        .mb-1 {
            margin-bottom: 2px;
        }
        .mb-2 {
            margin-bottom: 4px;
        }
        h3 {
            margin-top: 10px;
            margin-bottom: 4px;
            page-break-after: avoid;
        }
        h4 {
            margin-top: 6px;
            margin-bottom: 2px;
            page-break-after: avoid;
        }
        p {
            margin: 0 0 4px 0;
            text-align: justify;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .signature-table th {
            background-color: #031b4e;
            color: #ffffff;
            padding: 5px 8px;
            font-size: 7pt;
            text-align: center;
            border: 1px solid #031b4e;
        }
        .signature-box {
            width: 50%;
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
            background-color: #ffffff;
        }
        .cursive-signature {
            font-family: "DejaVu Serif", Georgia, serif;
            font-style: italic;
            font-weight: bold;
        }
        .green-verification-box {
            border: 1.5px solid #16a34a;
            background-color: #f0fdf4;
            padding: 7px 10px;
            margin-top: 8px;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        .note-box {
            border: 1px solid #bfdbfe;
            background-color: #eff6ff;
            padding: 6px 9px;
            margin-bottom: 10px;
            font-size: 7pt;
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    @if(!empty($logoBase64))
        <div class="watermark">
            <img src="{{ $logoBase64 }}" alt="Vedanta Watermark">
        </div>
    @endif

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">DIGITALLY SIGNED &amp; VERIFIED &mdash; STANDALONE EXECUTION</td>
                <td class="footer-right">
                    Agreement ID: {{ $agreement->agreement_code }} | Recipient: {{ $agreement->recipient_email }} | <span class="page-num"></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="header-section">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo-img" alt="Vedanta Logo"><br>
        @endif
        <h1 class="main-title">VEDANTA PLACEMENT AGENCY</h1>
        <div class="sub-service">SERVICE AGREEMENT</div>
        <div class="sub-desc">Recruitment, Placement Services &amp; Standard Terms and Conditions</div>
        <div class="sub-intent">THIS AGREEMENT is executed electronically via authenticated recipient signing link between the Agency and the Signatory below.</div>
    </div>

    <table class="info-table">
        <tr>
            <th>AGENCY</th>
            <td>Vedanta Placement Agency (Authorized Signatory: Aditya Rajveer &mdash; Founder &amp; CEO)</td>
        </tr>
        <tr>
            <th>AGREEMENT ID</th>
            <td><strong>{{ $agreement->agreement_code }}</strong></td>
        </tr>
        <tr>
            <th>SIGNATORY NAME</th>
            <td><strong>{{ $signerName }}</strong></td>
        </tr>
        <tr>
            <th>RECIPIENT EMAIL</th>
            <td>{{ $agreement->recipient_email }}</td>
        </tr>
        @if(!empty($agreement->recipient_phone))
            <tr>
                <th>CONTACT PHONE</th>
                <td>{{ $agreement->recipient_phone }}</td>
            </tr>
        @endif
        <tr>
            <th>AGREEMENT SENT DATE</th>
            <td>{{ $sentDateTime }}</td>
        </tr>
        <tr>
            <th>SIGNED DATE &amp; TIME</th>
            <td><strong>{{ $signedDateTime }}</strong></td>
        </tr>
        <tr>
            <th>EXECUTION MODE</th>
            <td>Standalone Direct Signing Link (Online / Digital Only)</td>
        </tr>
        <tr>
            <th>AGREEMENT STATUS</th>
            <td><strong style="color: #15803d;">DIGITALLY SIGNED &amp; VERIFIED</strong></td>
        </tr>
    </table>

    @if(!empty($agreement->admin_note))
        <div class="note-box">
            <strong>Agency Dispatch Note:</strong> {{ $agreement->admin_note }}
        </div>
    @endif

    @include('candidate.partials.agreement-text')

    <h4 class="font-bold text-sm text-text-main mt-4 mb-1">DIGITAL ACCEPTANCE &amp; EXECUTION RECORD</h4>

    <table class="info-table" style="margin-top: 4px;">
        <tr>
            <th>Signer Full Name</th>
            <td><strong>{{ $signerName }}</strong></td>
        </tr>
        <tr>
            <th>Signer Email</th>
            <td>{{ $agreement->recipient_email }}</td>
        </tr>
        <tr>
            <th>Acceptance Date &amp; Time</th>
            <td>{{ $signedDateTime }}</td>
        </tr>
        <tr>
            <th>IP / Device Audit Trail</th>
            <td>{{ $agreement->signer_ip ?? 'Captured Online' }} / {{ \Illuminate\Support\Str::limit($agreement->signer_user_agent ?? 'Web Browser', 65) }}</td>
        </tr>
        <tr>
            <th>Execution Status</th>
            <td><strong style="color: #15803d;">ACCEPTED ONLINE / DIGITALLY EXECUTED</strong></td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <th>RECIPIENT / SIGNATORY DIGITAL SIGNATURE</th>
            <th>AGENCY AUTHORIZED SIGNATORY</th>
        </tr>
        <tr>
            <td class="signature-box">
                <div style="font-size: 5.8pt; color: #6b7280; margin-bottom: 2px;">[Electronic signature captured via direct signing link]</div>
                <div style="font-size: 6.2pt; color: #374151; margin-bottom: 3px;">
                    Name: <strong>{{ $signerName }}</strong><br>
                    Email: {{ $agreement->recipient_email }}<br>
                    Date/Time: {{ $signedDateTime }}
                </div>

                <div style="text-align: center; min-height: 40px; padding-top: 3px;">
                    @if($agreement->signature_type === 'draw' && !empty($signatureSrc))
                        <img src="{{ $signatureSrc }}" style="max-height: 42px; max-width: 165px;" alt="Recipient Signature">
                    @else
                        <div class="cursive-signature" style="color: #111827; font-size: 18px;">
                            {{ $agreement->signature_data ?: $signerName }}
                        </div>
                    @endif
                </div>

                <div style="font-size: 5.8pt; color: #6b7280; text-align: center; margin-top: 3px;">
                    {{ $signerName }} &bull; {{ $agreement->signature_type === 'draw' ? 'Drawn Digital Signature' : 'Typed Digital Signature' }}
                </div>
            </td>
            <td class="signature-box">
                <div style="text-align: center; min-height: 40px; padding-top: 2px;">
                    @if(!empty($adminSignBase64))
                        <img src="{{ $adminSignBase64 }}" style="max-height: 40px; max-width: 170px; height: auto; width: auto; display: inline-block;" alt="Aditya Rajveer Signature">
                    @else
                        <div class="cursive-signature" style="font-size: 20px; color: #004d99;">Aditya Rajveer</div>
                    @endif
                </div>
                <div style="font-size: 6.5pt; font-weight: bold; color: #111827; text-align: center;">Digital Signature &mdash; Aditya Rajveer</div>
                <div style="font-size: 6.2pt; color: #374151; margin-top: 3px; line-height: 1.25;">
                    <strong>Aditya Rajveer</strong><br>
                    Founder &amp; CEO<br>
                    Vedanta Placement Agency<br>
                    <span style="color: #15803d; font-weight: bold; font-size: 6pt;">Digital authorization applied</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="green-verification-box">
        <div style="color: #15803d; font-weight: bold; font-size: 7.2pt;">&#10003; DIGITALLY SIGNED &amp; VERIFIED</div>
        <div style="font-size: 6.5pt; color: #166534; margin-top: 2px; line-height: 1.28;">
            Signed By: <strong>{{ $signerName }}</strong> ({{ $agreement->recipient_email }})<br>
            Executed At: {{ $signedDateTime }}<br>
            Agreement ID: [{{ $agreement->agreement_code }}]
        </div>
    </div>

    <div style="font-size: 5.8pt; font-weight: bold; color: #374151; text-align: center; margin-top: 5px;">
        ONLINE / DIGITAL EXECUTION ONLY &mdash; Electronic execution record generated automatically upon authenticated link submission.
    </div>
</body>
</html>
