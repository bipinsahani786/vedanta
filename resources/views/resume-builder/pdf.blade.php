<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 32px;
            margin: 0 0 5px 0;
            color: #111;
        }
        .header h2 {
            font-size: 18px;
            color: #555;
            margin: 0 0 10px 0;
            font-weight: normal;
        }
        .contact-info {
            font-size: 13px;
            color: #555;
        }
        .contact-info span {
            margin: 0 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #111;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            margin-bottom: 12px;
        }
        .summary p {
            text-align: justify;
            line-height: 1.5;
            margin: 0;
            white-space: pre-wrap;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-header {
            width: 100%;
        }
        .item-title {
            font-weight: bold;
            font-size: 15px;
            color: #111;
        }
        .item-date {
            float: right;
            font-style: italic;
            color: #666;
            font-size: 13px;
        }
        .item-subtitle {
            font-weight: 500;
            color: #444;
            margin: 3px 0 8px 0;
        }
        .item-desc {
            line-height: 1.5;
            margin: 0;
            white-space: pre-wrap;
        }
        .skills-list {
            line-height: 1.6;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    @if(!empty($data['personal']['photo']))
        <div class="header" style="text-align: left; padding-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; border: none; margin: 0; padding: 0;">
                <tr>
                    <td style="width: 100px; vertical-align: middle; border: none; padding: 0 20px 0 0;">
                        <img src="{{ $data['personal']['photo'] }}" style="width: 100px; height: 100px; border-radius: 8px; display: block; object-fit: cover;">
                    </td>
                    <td style="vertical-align: middle; border: none; padding: 0; text-align: left;">
                        <h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 32px; margin: 0 0 5px 0; color: #111;">{{ $data['personal']['name'] ?? 'Your Name' }}</h1>
                        @if(!empty($data['personal']['title']))
                            <h2 style="font-size: 18px; color: #555; margin: 0 0 10px 0; font-weight: normal;">{{ $data['personal']['title'] }}</h2>
                        @endif
                        <div class="contact-info" style="font-size: 13px; color: #555;">
                            @if(!empty($data['personal']['email']))
                                <span style="margin-right: 15px;">Email: {{ $data['personal']['email'] }}</span>
                            @endif
                            @if(!empty($data['personal']['phone']))
                                <span style="margin-right: 15px;">Phone: {{ $data['personal']['phone'] }}</span>
                            @endif
                            @if(!empty($data['personal']['location']))
                                <span>Location: {{ $data['personal']['location'] }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @else
        <div class="header">
            <h1>{{ $data['personal']['name'] ?? 'Your Name' }}</h1>
            @if(!empty($data['personal']['title']))
                <h2>{{ $data['personal']['title'] }}</h2>
            @endif
            
            <div class="contact-info">
                @if(!empty($data['personal']['email']))
                    <span>Email: {{ $data['personal']['email'] }}</span>
                @endif
                @if(!empty($data['personal']['phone']))
                    <span>Phone: {{ $data['personal']['phone'] }}</span>
                @endif
                @if(!empty($data['personal']['location']))
                    <span>Location: {{ $data['personal']['location'] }}</span>
                @endif
            </div>
        </div>
    @endif

    @if(!empty($data['summary']))
    <div class="section summary">
        <div class="section-title">Professional Summary</div>
        <p>{{ $data['summary'] }}</p>
    </div>
    @endif

    @if(!empty($data['experience']) && count($data['experience']) > 0)
    <div class="section">
        <div class="section-title">Experience</div>
        @foreach($data['experience'] as $exp)
        <div class="item">
            <div class="item-header">
                <span class="item-title">{{ $exp['title'] }}</span>
                <span class="item-date">{{ $exp['startDate'] }} - {{ $exp['endDate'] }}</span>
                <div class="clear"></div>
            </div>
            <div class="item-subtitle">{{ $exp['company'] }}</div>
            <div class="item-desc">{{ $exp['description'] }}</div>
        </div>
        @endforeach
    </div>
    @endif

    @if(!empty($data['education']) && count($data['education']) > 0)
    <div class="section">
        <div class="section-title">Education</div>
        @foreach($data['education'] as $edu)
        <div class="item" style="margin-bottom: 10px;">
            <div class="item-header">
                <span class="item-title">{{ $edu['degree'] }}</span>
                <span class="item-date">{{ $edu['year'] }}</span>
                <div class="clear"></div>
            </div>
            <div style="width: 100%;">
                <span style="color: #444;">{{ $edu['institution'] }}</span>
                <span style="float: right; font-weight: bold;">{{ $edu['grade'] }}</span>
                <div class="clear"></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(!empty($data['skills']) && count($data['skills']) > 0)
    <div class="section">
        <div class="section-title">Skills</div>
        <div class="skills-list">
            @foreach($data['skills'] as $skill)
                <span style="margin-right: 15px;">• {{ $skill }}</span>
            @endforeach
        </div>
    </div>
    @endif

</body>
</html>
