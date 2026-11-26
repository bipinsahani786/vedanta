<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume - {{ $data['personal']['name'] ?? 'Candidate' }}</title>
    @php
        $tpl = $templateMeta ?? ['id' => 'corporate_classic', 'layout' => 'classic', 'order' => 'experience_first', 'primary' => '#111827', 'secondary' => '#f3f4f6', 'font' => 'serif'];
        $layout = $tpl['layout'] ?? 'classic';
        $order = $tpl['order'] ?? 'experience_first';
        $primary = $tpl['primary'] ?? '#111827';
        $secondary = $tpl['secondary'] ?? '#3b82f6';
        $fontFamily = ($tpl['font'] ?? 'sans') === 'serif' ? "'DejaVu Serif', Georgia, serif" : "'DejaVu Sans', Arial, Helvetica, sans-serif";
        $personal = $data['personal'] ?? [];
        $summary = $data['summary'] ?? '';
        $experiences = $data['experience'] ?? [];
        $educations = $data['education'] ?? [];
        $skills = $data['skills'] ?? [];
        $certifications = $data['certifications'] ?? [];
    @endphp
    <style>
        @page { margin: 0; }
        body {
            font-family: {{ $fontFamily }};
            color: #1f2937;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.45;
        }
        .page-pad {
            padding: 28px 34px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: {{ $primary }};
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }
        .item {
            margin-bottom: 11px;
        }
        .item-title {
            font-weight: bold;
            font-size: 13px;
            color: #111827;
        }
        .item-date {
            float: right;
            font-size: 11px;
            color: #6b7280;
            font-weight: bold;
        }
        .item-subtitle {
            font-size: 11.5px;
            font-weight: bold;
            color: #4b5563;
            margin: 2px 0 4px 0;
        }
        .item-desc {
            font-size: 11.5px;
            color: #374151;
            white-space: pre-wrap;
        }
        .skill-chip {
            display: inline-block;
            padding: 3px 8px;
            margin: 0 5px 5px 0;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background: #f9fafb;
            font-size: 10.5px;
            color: #111827;
        }
        .clear { clear: both; }
    </style>
</head>
<body>

@if($layout === 'sidebar')
    <table style="width: 100%; border-collapse: collapse; min-height: 100%;">
        <tr>
            <td style="width: 34%; vertical-align: top; background: {{ $primary }}; color: #ffffff; padding: 28px 20px;">
                <div style="text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 16px; margin-bottom: 16px;">
                    @if(!empty($personal['photo']))
                        <img src="{{ $personal['photo'] }}" style="width: 88px; height: 88px; border-radius: 10px; object-fit: cover; margin-bottom: 10px;">
                    @endif
                    <div style="font-size: 18px; font-weight: bold; color: #ffffff;">{{ $personal['name'] ?? 'Your Name' }}</div>
                    @if(!empty($personal['title']))
                        <div style="font-size: 11px; color: {{ $secondary }}; margin-top: 4px; font-weight: bold;">{{ $personal['title'] }}</div>
                    @endif
                </div>

                <div style="margin-bottom: 18px;">
                    <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #cbd5e1; font-weight: bold; margin-bottom: 6px;">Contact</div>
                    @if(!empty($personal['email']))
                        <div style="font-size: 10.5px; margin-bottom: 4px; word-break: break-all;">{{ $personal['email'] }}</div>
                    @endif
                    @if(!empty($personal['phone']))
                        <div style="font-size: 10.5px; margin-bottom: 4px;">{{ $personal['phone'] }}</div>
                    @endif
                    @if(!empty($personal['location']))
                        <div style="font-size: 10.5px;">{{ $personal['location'] }}</div>
                    @endif
                </div>

                @if(count($skills) > 0)
                    <div style="margin-bottom: 18px;">
                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #cbd5e1; font-weight: bold; margin-bottom: 6px;">Key Skills</div>
                        @foreach($skills as $skill)
                            <span style="display: inline-block; background: rgba(255,255,255,0.14); color: #ffffff; padding: 3px 7px; border-radius: 4px; font-size: 10px; margin: 0 4px 4px 0;">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif

                @if(count($certifications) > 0)
                    <div>
                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #cbd5e1; font-weight: bold; margin-bottom: 6px;">Certifications</div>
                        @foreach($certifications as $cert)
                            <div style="font-size: 10.5px; margin-bottom: 3px;">• {{ $cert }}</div>
                        @endforeach
                    </div>
                @endif
            </td>

            <td style="width: 66%; vertical-align: top; padding: 28px 26px; background: #ffffff;">
                @if(!empty($summary))
                    <div class="section">
                        <div class="section-title" style="border-bottom-color: {{ $secondary }};">Professional Profile</div>
                        <div style="white-space: pre-wrap; color: #374151;">{{ $summary }}</div>
                    </div>
                @endif

                @if(count($experiences) > 0)
                    <div class="section">
                        <div class="section-title" style="border-bottom-color: {{ $secondary }};">Experience</div>
                        @foreach($experiences as $exp)
                            <div class="item">
                                <div>
                                    <span class="item-title">{{ $exp['title'] ?? '' }}</span>
                                    <span class="item-date">{{ $exp['startDate'] ?? '' }} - {{ $exp['endDate'] ?? '' }}</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="item-subtitle" style="color: {{ $secondary }};">{{ $exp['company'] ?? '' }}</div>
                                @if(!empty($exp['description']))
                                    <div class="item-desc">{{ $exp['description'] }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($educations) > 0)
                    <div class="section">
                        <div class="section-title" style="border-bottom-color: {{ $secondary }};">Education</div>
                        @foreach($educations as $edu)
                            <div class="item">
                                <div>
                                    <span class="item-title">{{ $edu['degree'] ?? '' }}</span>
                                    <span class="item-date">{{ $edu['year'] ?? '' }}</span>
                                    <div class="clear"></div>
                                </div>
                                <div>
                                    <span style="color: #4b5563;">{{ $edu['institution'] ?? '' }}</span>
                                    @if(!empty($edu['grade']))
                                        <span style="float: right; font-weight: bold;">{{ $edu['grade'] }}</span>
                                    @endif
                                    <div class="clear"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </td>
        </tr>
    </table>
@else
    {{-- Banner or Classic Single-Column Layout --}}
    @if($layout === 'banner')
        <div style="background: {{ $primary }}; color: #ffffff; padding: 26px 34px; border-bottom: 4px solid {{ $secondary }};">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @if(!empty($personal['photo']))
                        <td style="width: 96px; vertical-align: middle; padding-right: 16px;">
                            <img src="{{ $personal['photo'] }}" style="width: 84px; height: 84px; border-radius: 8px; object-fit: cover;">
                        </td>
                    @endif
                    <td style="vertical-align: middle;">
                        <div style="font-size: 24px; font-weight: bold;">{{ $personal['name'] ?? 'Your Name' }}</div>
                        @if(!empty($personal['title']))
                            <div style="font-size: 13px; color: #e2e8f0; margin-top: 3px;">{{ $personal['title'] }}</div>
                        @endif
                        <div style="font-size: 11px; color: #cbd5e1; margin-top: 8px;">
                            @if(!empty($personal['email'])) <span style="margin-right: 14px;">Email: {{ $personal['email'] }}</span> @endif
                            @if(!empty($personal['phone'])) <span style="margin-right: 14px;">Phone: {{ $personal['phone'] }}</span> @endif
                            @if(!empty($personal['location'])) <span>Location: {{ $personal['location'] }}</span> @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @else
        <div style="padding: 26px 34px 16px 34px; border-bottom: 2px solid {{ $primary }}; text-align: {{ !empty($personal['photo']) ? 'left' : 'center' }};">
            @if(!empty($personal['photo']))
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 96px; vertical-align: middle; padding-right: 16px;">
                            <img src="{{ $personal['photo'] }}" style="width: 84px; height: 84px; border-radius: 8px; object-fit: cover;">
                        </td>
                        <td style="vertical-align: middle;">
                            <div style="font-size: 26px; font-weight: bold; color: #111827;">{{ $personal['name'] ?? 'Your Name' }}</div>
                            @if(!empty($personal['title']))
                                <div style="font-size: 14px; color: {{ $primary }}; margin-top: 3px;">{{ $personal['title'] }}</div>
                            @endif
                            <div style="font-size: 11px; color: #4b5563; margin-top: 6px;">
                                @if(!empty($personal['email'])) <span style="margin-right: 14px;">Email: {{ $personal['email'] }}</span> @endif
                                @if(!empty($personal['phone'])) <span style="margin-right: 14px;">Phone: {{ $personal['phone'] }}</span> @endif
                                @if(!empty($personal['location'])) <span>Location: {{ $personal['location'] }}</span> @endif
                            </div>
                        </td>
                    </tr>
                </table>
            @else
                <div style="font-size: 26px; font-weight: bold; color: #111827;">{{ $personal['name'] ?? 'Your Name' }}</div>
                @if(!empty($personal['title']))
                    <div style="font-size: 14px; color: {{ $primary }}; margin-top: 3px;">{{ $personal['title'] }}</div>
                @endif
                <div style="font-size: 11px; color: #4b5563; margin-top: 7px;">
                    @if(!empty($personal['email'])) <span style="margin: 0 8px;">Email: {{ $personal['email'] }}</span> @endif
                    @if(!empty($personal['phone'])) <span style="margin: 0 8px;">Phone: {{ $personal['phone'] }}</span> @endif
                    @if(!empty($personal['location'])) <span style="margin: 0 8px;">Location: {{ $personal['location'] }}</span> @endif
                </div>
            @endif
        </div>
    @endif

    <div class="page-pad">
        @if(!empty($summary))
            <div class="section">
                <div class="section-title">{{ $order === 'education_first' ? 'Career Objective' : 'Professional Summary' }}</div>
                <div style="white-space: pre-wrap; color: #374151; text-align: justify;">{{ $summary }}</div>
            </div>
        @endif

        @if($order === 'education_first')
            @if(count($educations) > 0)
                <div class="section">
                    <div class="section-title">Education &amp; Academics</div>
                    @foreach($educations as $edu)
                        <div class="item">
                            <div>
                                <span class="item-title">{{ $edu['degree'] ?? '' }}</span>
                                <span class="item-date">{{ $edu['year'] ?? '' }}</span>
                                <div class="clear"></div>
                            </div>
                            <div>
                                <span style="color: #4b5563;">{{ $edu['institution'] ?? '' }}</span>
                                @if(!empty($edu['grade']))
                                    <span style="float: right; font-weight: bold;">{{ $edu['grade'] }}</span>
                                @endif
                                <div class="clear"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($skills) > 0)
                <div class="section">
                    <div class="section-title">Core Skills</div>
                    @foreach($skills as $skill)
                        <span class="skill-chip">{{ $skill }}</span>
                    @endforeach
                </div>
            @endif

            @if(count($experiences) > 0)
                <div class="section">
                    <div class="section-title">Experience &amp; Internships</div>
                    @foreach($experiences as $exp)
                        <div class="item">
                            <div>
                                <span class="item-title">{{ $exp['title'] ?? '' }}</span>
                                <span class="item-date">{{ $exp['startDate'] ?? '' }} - {{ $exp['endDate'] ?? '' }}</span>
                                <div class="clear"></div>
                            </div>
                            <div class="item-subtitle">{{ $exp['company'] ?? '' }}</div>
                            @if(!empty($exp['description']))
                                <div class="item-desc">{{ $exp['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            @if(count($experiences) > 0)
                <div class="section">
                    <div class="section-title">Work Experience</div>
                    @foreach($experiences as $exp)
                        <div class="item">
                            <div>
                                <span class="item-title">{{ $exp['title'] ?? '' }}</span>
                                <span class="item-date">{{ $exp['startDate'] ?? '' }} - {{ $exp['endDate'] ?? '' }}</span>
                                <div class="clear"></div>
                            </div>
                            <div class="item-subtitle">{{ $exp['company'] ?? '' }}</div>
                            @if(!empty($exp['description']))
                                <div class="item-desc">{{ $exp['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($educations) > 0)
                <div class="section">
                    <div class="section-title">Education</div>
                    @foreach($educations as $edu)
                        <div class="item">
                            <div>
                                <span class="item-title">{{ $edu['degree'] ?? '' }}</span>
                                <span class="item-date">{{ $edu['year'] ?? '' }}</span>
                                <div class="clear"></div>
                            </div>
                            <div>
                                <span style="color: #4b5563;">{{ $edu['institution'] ?? '' }}</span>
                                @if(!empty($edu['grade']))
                                    <span style="float: right; font-weight: bold;">{{ $edu['grade'] }}</span>
                                @endif
                                <div class="clear"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($skills) > 0)
                <div class="section">
                    <div class="section-title">Skills</div>
                    @foreach($skills as $skill)
                        <span class="skill-chip">{{ $skill }}</span>
                    @endforeach
                </div>
            @endif
        @endif

        @if(count($certifications) > 0)
            <div class="section">
                <div class="section-title">Certifications &amp; Projects</div>
                @foreach($certifications as $cert)
                    <div style="font-size: 11.5px; margin-bottom: 3px;">• {{ $cert }}</div>
                @endforeach
            </div>
        @endif
    </div>
@endif

</body>
</html>
