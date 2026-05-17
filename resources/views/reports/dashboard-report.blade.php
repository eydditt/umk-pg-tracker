<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>UMK PG Tracker — Dashboard Report</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans',sans-serif; font-size:9px; color:#1C2B2A; background:#ffffff; }

/* reusable — only non-color structural styles here */
.w100  { width:100%; }
.track { background:#E0ECEA; border-radius:3px; height:6px; margin-top:3px; margin-bottom:7px; }
.fill  { height:6px; border-radius:3px; }
.mtrack{ background:#E0ECEA; border-radius:3px; height:5px; margin-top:2px; margin-bottom:5px; }
.mfill { height:5px; border-radius:3px; }
.dt    { width:100%; border-collapse:collapse; font-size:8px; }
.dt th { background:#0D2B28; color:rgba(255,255,255,0.9); padding:5px 8px; text-align:left; font-size:7px; text-transform:uppercase; letter-spacing:0.4px; font-weight:700; }
.dt td { padding:5px 8px; color:#2C3E3D; border-bottom:1px solid #E8F0EF; }
.dt .alt td { background:#EFF5F5; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════
     HEADER
══════════════════════════════════════ --}}
<div style="background-color:#0D2B28; padding:16px 20px 14px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="bottom">
                <div style="font-size:7px; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:3px;">
                    Universiti Malaysia Kelantan &nbsp;&middot;&nbsp; Faculty of Data Science and Computing (FSDK)
                </div>
                <div style="font-size:17px; font-weight:700; color:#ffffff; line-height:1.15;">UMK PG Tracker</div>
                <div style="font-size:8px; color:rgba(255,255,255,0.6); margin-top:3px;">Postgraduate Student Dashboard Report</div>
                <div style="margin-top:5px;">
                    <span style="background-color:#2A9D8F; color:#ffffff; font-size:6.5px; font-weight:700; letter-spacing:1px; text-transform:uppercase; padding:2px 8px; border-radius:2px;">
                        Confidential &nbsp;&middot;&nbsp; Internal Use Only
                    </span>
                </div>
            </td>
            <td valign="bottom" width="160" style="text-align:right;">
                <div style="font-size:8px; color:rgba(255,255,255,0.55); line-height:1.7;">
                    Report Generated<br>
                    <strong style="color:#ffffff; font-size:9px;">{{ $generated_at }}</strong>
                </div>
            </td>
        </tr>
    </table>
</div>
<div style="height:3px; background-color:#2A9D8F;"></div>
<div style="height:1px; background-color:#E9C46A;"></div>

{{-- ══════════════════════════════════════
     PAGE BODY
══════════════════════════════════════ --}}
<div style="padding:14px 18px;">

    {{-- ── SECTION 1: OVERVIEW ── --}}
    <div style="margin-bottom:8px;">
        <div style="border-left:3px solid #2A9D8F; padding-left:7px;">
            <div style="font-size:7px; font-weight:700; color:#2A9D8F; text-transform:uppercase; letter-spacing:1.2px;">Overview Statistics</div>
            <div style="font-size:7px; color:#8a9a99; margin-top:1px;">Snapshot of current postgraduate programme data</div>
        </div>
        <div style="height:1px; background-color:#E8F0EF; margin-top:4px;"></div>
    </div>

    <table width="100%" cellspacing="5" cellpadding="0" style="border-collapse:separate; margin-bottom:4px;">
        <tr>
            <td width="16%" valign="top">
                <div style="background-color:#FFFBF0; border:1px solid #DDE8E7; border-top:3px solid #F59E0B; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $pending_applicants }}</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Pending Applicants</div>
                    <div style="font-size:7px; color:#9BB5B3;">{{ $total_applicants }} total submitted</div>
                </div>
            </td>
            <td width="16%" valign="top">
                <div style="background-color:#F0FAF9; border:1px solid #DDE8E7; border-top:3px solid #2A9D8F; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $total_students }}</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Total Students</div>
                    <div style="font-size:7px; color:#9BB5B3;">{{ $active_students }} active</div>
                </div>
            </td>
            <td width="16%" valign="top">
                <div style="background-color:#F0F6FF; border:1px solid #DDE8E7; border-top:3px solid #3B82F6; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $total_lecturers }}</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Lecturers</div>
                    <div style="font-size:7px; color:#9BB5B3;">Total registered</div>
                </div>
            </td>
            <td width="16%" valign="top">
                <div style="background-color:#F0FAF5; border:1px solid #DDE8E7; border-top:3px solid #10B981; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $completed_students }}</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Graduated</div>
                    <div style="font-size:7px; color:#9BB5B3;">Completed program</div>
                </div>
            </td>
            <td width="16%" valign="top">
                <div style="background-color:#FFF5F5; border:1px solid #DDE8E7; border-top:3px solid #EF4444; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $total_students > 0 ? round(($unsupervised/$total_students)*100) : 0 }}%</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Unsupervised</div>
                    <div style="font-size:7px; color:#9BB5B3;">{{ $unsupervised }} without SV</div>
                </div>
            </td>
            <td width="16%" valign="top">
                <div style="background-color:#F8F4FF; border:1px solid #DDE8E7; border-top:3px solid #7C3AED; border-radius:5px; padding:10px 8px; text-align:center;">
                    <div style="font-size:20px; font-weight:700; color:#0D2B28; line-height:1; margin-bottom:4px;">{{ $total_students > 0 ? round(($pending_english/$total_students)*100) : 0 }}%</div>
                    <div style="font-size:6.5px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:3px;">Pending English</div>
                    <div style="font-size:7px; color:#9BB5B3;">{{ $pending_english }} students</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ── SECTION 2: DEMOGRAPHICS ── --}}
    <div style="margin-bottom:8px; margin-top:14px;">
        <div style="border-left:3px solid #2A9D8F; padding-left:7px;">
            <div style="font-size:7px; font-weight:700; color:#2A9D8F; text-transform:uppercase; letter-spacing:1.2px;">Student Demographics</div>
            <div style="font-size:7px; color:#8a9a99; margin-top:1px;">Breakdown by programme, gender, origin and funding</div>
        </div>
        <div style="height:1px; background-color:#E8F0EF; margin-top:4px;"></div>
    </div>

    @php
        $ptotal   = max($phd_students + $master_students, 1);
        $gtotal   = max($male_students + $female_students, 1);
        $ototal   = max($local_students + $international_students, 1);
        $payTotal = max($payment_scholarship + $payment_self + $payment_other + $payment_not_stated, 1);
        $payments = [
            ['Scholarship', $payment_scholarship, '#2A9D8F'],
            ['Self-funded',  $payment_self,        '#F4A261'],
            ['Other',        $payment_other,       '#E76F51'],
            ['Not-stated',   $payment_not_stated,  '#9BB5B3'],
        ];
    @endphp

    <table width="100%" cellspacing="5" cellpadding="0" style="border-collapse:separate; margin-bottom:4px;">
        <tr>

            {{-- Program Type --}}
            <td width="25%" valign="top">
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:10px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:6px; border-bottom:1px solid #E0ECEA;">Program Type</div>
                    <table width="100%" cellpadding="2" cellspacing="0" style="margin-bottom:6px;">
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#7C3AED; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">PhD</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $phd_students }}</strong> <span style="color:#9BB5B3;">({{ round(($phd_students/$ptotal)*100) }}%)</span></td>
                        </tr>
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#0891B2; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">Master</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $master_students }}</strong> <span style="color:#9BB5B3;">({{ round(($master_students/$ptotal)*100) }}%)</span></td>
                        </tr>
                    </table>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">PhD</div>
                    <div class="mtrack"><div class="mfill" style="width:{{ round(($phd_students/$ptotal)*100) }}%; background-color:#7C3AED;"></div></div>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">Master</div>
                    <div class="mtrack" style="margin-bottom:0;"><div class="mfill" style="width:{{ round(($master_students/$ptotal)*100) }}%; background-color:#0891B2;"></div></div>
                </div>
            </td>

            {{-- Gender --}}
            <td width="25%" valign="top">
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:10px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:6px; border-bottom:1px solid #E0ECEA;">Gender Distribution</div>
                    <table width="100%" cellpadding="2" cellspacing="0" style="margin-bottom:6px;">
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#2A9D8F; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">Male</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $male_students }}</strong> <span style="color:#9BB5B3;">({{ round(($male_students/$gtotal)*100) }}%)</span></td>
                        </tr>
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#E76F51; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">Female</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $female_students }}</strong> <span style="color:#9BB5B3;">({{ round(($female_students/$gtotal)*100) }}%)</span></td>
                        </tr>
                    </table>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">Male</div>
                    <div class="mtrack"><div class="mfill" style="width:{{ round(($male_students/$gtotal)*100) }}%; background-color:#2A9D8F;"></div></div>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">Female</div>
                    <div class="mtrack" style="margin-bottom:0;"><div class="mfill" style="width:{{ round(($female_students/$gtotal)*100) }}%; background-color:#E76F51;"></div></div>
                </div>
            </td>

            {{-- Origin --}}
            <td width="25%" valign="top">
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:10px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:6px; border-bottom:1px solid #E0ECEA;">Student Origin</div>
                    <table width="100%" cellpadding="2" cellspacing="0" style="margin-bottom:6px;">
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#E9C46A; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">Local</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $local_students }}</strong> <span style="color:#9BB5B3;">({{ round(($local_students/$ototal)*100) }}%)</span></td>
                        </tr>
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:#264653; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">International</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $international_students }}</strong> <span style="color:#9BB5B3;">({{ round(($international_students/$ototal)*100) }}%)</span></td>
                        </tr>
                    </table>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">Local</div>
                    <div class="mtrack"><div class="mfill" style="width:{{ round(($local_students/$ototal)*100) }}%; background-color:#E9C46A;"></div></div>
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">International</div>
                    <div class="mtrack" style="margin-bottom:0;"><div class="mfill" style="width:{{ round(($international_students/$ototal)*100) }}%; background-color:#264653;"></div></div>
                </div>
            </td>

            {{-- Payment --}}
            <td width="25%" valign="top">
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:10px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:6px; border-bottom:1px solid #E0ECEA;">Payment Method</div>
                    <table width="100%" cellpadding="2" cellspacing="0" style="margin-bottom:6px;">
                        @foreach($payments as [$plbl, $pval, $pcol])
                        <tr>
                            <td width="13"><span style="width:7px; height:7px; border-radius:50%; background-color:{{ $pcol }}; display:inline-block; vertical-align:middle;"></span></td>
                            <td style="font-size:8.5px; color:#2C3E3D;">{{ $plbl }}</td>
                            <td align="right" style="font-size:8.5px;"><strong style="color:#0D2B28;">{{ $pval }}</strong> <span style="color:#9BB5B3;">({{ round(($pval/$payTotal)*100) }}%)</span></td>
                        </tr>
                        @endforeach
                    </table>
                    @foreach($payments as [$plbl, $pval, $pcol])
                    <div style="font-size:6.5px; color:#9BB5B3; margin-bottom:2px;">{{ $plbl }}</div>
                    <div class="mtrack"><div class="mfill" style="width:{{ round(($pval/$payTotal)*100) }}%; background-color:{{ $pcol }};"></div></div>
                    @endforeach
                </div>
            </td>

        </tr>
    </table>

    {{-- ── SECTION 3: ANALYTICS ── --}}
    <div style="margin-bottom:8px; margin-top:14px;">
        <div style="border-left:3px solid #2A9D8F; padding-left:7px;">
            <div style="font-size:7px; font-weight:700; color:#2A9D8F; text-transform:uppercase; letter-spacing:1.2px;">Analytics</div>
            <div style="font-size:7px; color:#8a9a99; margin-top:1px;">Status distribution, milestones, supervision and intake trends</div>
        </div>
        <div style="height:1px; background-color:#E8F0EF; margin-top:4px;"></div>
    </div>

    <table width="100%" cellspacing="5" cellpadding="0" style="border-collapse:separate;">
        <tr>

            {{-- LEFT COLUMN --}}
            <td width="50%" valign="top">

                {{-- Status Distribution --}}
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:11px 12px; margin-bottom:6px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:7px; border-bottom:1px solid #E0ECEA;">Student Status Distribution</div>
                    @php
                        $statuses = [
                            ['Active',     $active_students,     '#10B981'],
                            ['Completed',  $completed_students,  '#2A9D8F'],
                            ['Deferred',   $deferred_students,   '#F59E0B'],
                            ['Terminated', $terminated_students, '#EF4444'],
                        ];
                        $maxS = max(array_column($statuses, 1)) ?: 1;
                    @endphp
                    @foreach($statuses as [$lbl, $cnt, $col])
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size:8px; color:#2C3E3D;">
                                <span style="width:7px; height:7px; border-radius:50%; background-color:{{ $col }}; display:inline-block; vertical-align:middle; margin-right:4px;"></span>{{ $lbl }}
                            </td>
                            <td align="right" style="font-size:8px;">
                                <strong style="color:#0D2B28;">{{ $cnt }}</strong>
                                <span style="color:#9BB5B3;">&nbsp;({{ $total_students > 0 ? round(($cnt/$total_students)*100) : 0 }}%)</span>
                            </td>
                        </tr>
                    </table>
                    <div class="track"><div class="fill" style="width:{{ round(($cnt/$maxS)*100) }}%; background-color:{{ $col }};"></div></div>
                    @endforeach
                </div>

                {{-- Milestones --}}
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:11px 12px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:7px; border-bottom:1px solid #E0ECEA;">Progress Milestones Completion</div>
                    @php
                        $milestones = [
                            ['English Test',    $passed_english,     '#2A9D8F'],
                            ['Pre-Viva',        $pre_viva_completed, '#7C3AED'],
                            ['Viva',            $viva_completed,     '#E9C46A'],
                            ['Degree Verified', $degree_verified,    '#E76F51'],
                        ];
                    @endphp
                    @foreach($milestones as [$lbl, $cnt, $col])
                    @php $mpct = $total_students > 0 ? round(($cnt/$total_students)*100) : 0; @endphp
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size:8px; color:#2C3E3D;">
                                <span style="width:7px; height:7px; border-radius:50%; background-color:{{ $col }}; display:inline-block; vertical-align:middle; margin-right:4px;"></span>{{ $lbl }}
                            </td>
                            <td align="right" style="font-size:8px;">
                                <strong style="color:#0D2B28;">{{ $cnt }} / {{ $total_students }}</strong>
                                <span style="color:#9BB5B3;">&nbsp;{{ $mpct }}%</span>
                            </td>
                        </tr>
                    </table>
                    <div class="track"><div class="fill" style="width:{{ $mpct }}%; background-color:{{ $col }};"></div></div>
                    @endforeach
                </div>

            </td>

            {{-- RIGHT COLUMN --}}
            <td width="50%" valign="top">

                {{-- Top Supervisors --}}
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:11px 12px; margin-bottom:6px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:7px; border-bottom:1px solid #E0ECEA;">Top Supervisors by Student Count</div>
                    @if($top_supervisors->isEmpty())
                        <p style="font-size:8px; color:#9BB5B3; text-align:center; padding:8px 0;">No supervisors assigned yet</p>
                    @else
                        @php $maxSv = $top_supervisors->max('main_students_count') ?: 1; @endphp
                        @foreach($top_supervisors as $sv)
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
                            <tr>
                                <td width="110" align="right" valign="middle" style="padding-right:6px; font-size:7.5px; color:#2C3E3D;">{{ $sv->full_name }}</td>
                                <td valign="middle">
                                    <div style="background-color:#E0ECEA; border-radius:2px; height:10px;">
                                        <div style="width:{{ round(($sv->main_students_count/$maxSv)*100) }}%; height:10px; border-radius:2px; background-color:#2A9D8F;"></div>
                                    </div>
                                </td>
                                <td width="20" align="left" valign="middle" style="padding-left:5px; font-size:7.5px; font-weight:700; color:#0D2B28;">{{ $sv->main_students_count }}</td>
                            </tr>
                        </table>
                        @endforeach
                    @endif
                </div>

                {{-- Intake Table --}}
                <div style="background-color:#F7FAFA; border:1px solid #DDE8E7; border-radius:5px; padding:11px 12px;">
                    <div style="font-size:7px; font-weight:700; color:#5a7370; text-transform:uppercase; letter-spacing:0.8px; padding-bottom:5px; margin-bottom:7px; border-bottom:1px solid #E0ECEA;">Student Intake by Session</div>
                    <table class="dt">
                        <thead>
                            <tr>
                                <th>Intake Session</th>
                                <th style="text-align:center; width:55px;">Students</th>
                                <th style="text-align:center; width:45px;">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($intake_data as $i => $row)
                            <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                                <td>{{ $row->intake_session ?? 'Not Set' }}</td>
                                <td style="text-align:center; font-weight:700; color:#0D2B28;">{{ $row->total }}</td>
                                <td style="text-align:center; font-weight:700; color:#2A9D8F;">{{ $total_students > 0 ? round(($row->total/$total_students)*100) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align:center; color:#9BB5B3; padding:10px 8px;">No intake data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </td>
        </tr>
    </table>

</div>{{-- /page-body --}}

{{-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ --}}
<div style="background-color:#0D2B28; padding:8px 18px; margin-top:14px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="font-size:6.5px; color:rgba(255,255,255,0.4);">
                UMK PG Tracker &nbsp;&middot;&nbsp; Postgraduate Student Management System &nbsp;&middot;&nbsp; FSDK &nbsp;&middot;&nbsp; Universiti Malaysia Kelantan
            </td>
            <td style="font-size:6.5px; color:rgba(255,255,255,0.4); text-align:right;">
                <span style="color:#2A9D8F; font-weight:700;">CONFIDENTIAL</span> &nbsp;&middot;&nbsp; Generated: {{ $generated_at }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>