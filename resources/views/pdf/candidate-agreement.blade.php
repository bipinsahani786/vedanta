<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidate Agreement</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #004d99;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #004d99;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .content h3 {
            color: #004d99;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        .content p {
            font-size: 14px;
            margin-bottom: 15px;
            text-align: justify;
        }
        .candidate-details {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .candidate-details table {
            width: 100%;
        }
        .candidate-details td {
            padding: 5px 0;
            font-size: 14px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-box {
            border-top: 1px solid #333;
            width: 250px;
            padding-top: 5px;
            text-align: center;
            font-size: 14px;
        }
        .signature-img {
            max-width: 250px;
            max-height: 100px;
            margin-bottom: 10px;
        }
        .date {
            margin-top: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>VEDANTA PLACEMENT AGENCY</h1>
        <p>Candidate Placement Agreement</p>
    </div>

    <div class="candidate-details">
        <table>
            <tr>
                <td><strong>Candidate Name:</strong> {{ $user->name }}</td>
                <td><strong>Date:</strong> {{ $date }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong> {{ $user->email }}</td>
                <td><strong>Phone:</strong> {{ $user->phone }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Address:</strong> {{ $profile->address }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>This Agreement is entered into between Vedanta Placement Agency ("Agency") and <strong>{{ $user->name }}</strong> ("Candidate") on the date specified above.</p>
        
        <h3>1. Services Provided</h3>
        <p>The Agency agrees to provide placement assistance services to the Candidate by matching their profile with suitable job openings available with associated schools and educational institutions.</p>
        
        <h3>2. Candidate Obligations</h3>
        <p>The Candidate affirms that all information provided in their profile, including educational qualifications, address, and experience, is true and accurate. Any misrepresentation may lead to immediate termination of services and possible legal action.</p>
        
        <h3>3. Fees and Payment</h3>
        <p>The Candidate agrees to pay the non-refundable registration and processing fee as selected in their payment plan. Furthermore, if placed successfully through the Agency, the Candidate agrees to pay the stipulated service charge (e.g., 50% of the first month's salary) within 15 days of joining the institution.</p>
        
        <h3>4. Confidentiality</h3>
        <p>Both parties agree to maintain strict confidentiality regarding job details, salary negotiations, and personal information shared during the recruitment process.</p>
        
        <h3>5. Termination</h3>
        <p>This agreement can be terminated by either party with a written notice. However, any pending service charges for successful placements made prior to termination will remain fully payable.</p>
        
        <p style="margin-top: 40px; font-weight: bold; font-style: italic;">
            By signing below, I acknowledge that I have read, understood, and agree to be bound by the terms and conditions outlined in this Agreement.
        </p>
    </div>

    <div class="signature-section">
        <img src="{{ $signature }}" class="signature-img" alt="Digital Signature">
        <div class="signature-box">
            {{ $user->name }}<br>
            <span style="font-size: 12px; color: #666;">(Digital Signature)</span>
        </div>
    </div>

    <div class="date">
        <strong>Date of Execution:</strong> {{ $date }}
    </div>

</body>
</html>
