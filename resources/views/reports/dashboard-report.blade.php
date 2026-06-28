<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>UMK PG Tracker — Dashboard Report</title>
<style>
    @page { margin: 0px; }

    * { margin:0; padding:0; box-sizing:border-box; }

    body { font-family:'Helvetica', Arial, sans-serif; font-size:12px; color:#1C2B2A; background:#ffffff; line-height: 1.32; margin-bottom: 40px; }

    .w100  { width:100%; }
    .track { background:#E0ECEA; border-radius:3px; height:8px; margin-top:3px; margin-bottom:7px; width: 100%; }
    .fill  { height:8px; border-radius:3px; }
    .mtrack{ background:#E0ECEA; border-radius:3px; height:7px; margin-top:2px; margin-bottom:6px; width: 100%; }
    .mfill { height:7px; border-radius:3px; }

    .dt    { width:100%; border-collapse:collapse; font-size:11px; margin-top:6px; }
    .dt th { background:#008080; color:#ffffff; padding:6px 10px; text-align:left; font-size:10px; text-transform:uppercase; font-weight:bold; }
    .dt td { padding:6px 10px; color:#2C3E3D; border-bottom:1px solid #E8F0EF; }
    .dt .alt td { background:#F7FAFA; }

    .card { background-color:#ffffff; border:1px solid #DDE8E7; border-radius:6px; padding:12px 14px; margin-bottom: 10px; }
    .card-title { font-size:10px; font-weight:bold; color:#008080; text-transform:uppercase; letter-spacing:0.8px; border-bottom:1px solid #E0ECEA; padding-bottom:4px; margin-bottom:8px; }

    .stat-box { border-radius:6px; padding:11px 10px; text-align:center; border: 1px solid #DDE8E7; }
    .stat-val { font-size:30px; font-weight:bold; color:#0D2B28; margin-bottom:4px; line-height:1; }
    .stat-lbl { font-size:9px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px; }
    .stat-sub { font-size:9px; color:#9BB5B3; }

    .footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        background-color: #008080;
        padding: 8px 24px;
    }
</style>
</head>
<body>

{{-- HEADER --}}
<div style="background-color:#20b2aa; padding:16px 24px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="bottom">
                <div style="font-size:9px; color:#B2DFDB; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:3px;">
                    Universiti Malaysia Kelantan &nbsp;|&nbsp; FSDK
                </div>
                <div style="font-size:22px; font-weight:bold; color:#ffffff;">UMK PG Tracker</div>
                <div style="font-size:11px; color:#E0F2F1; margin-top:2px;">Postgraduate Student Dashboard Executive Report</div>
            </td>
            <td valign="bottom" align="right">
                <span style="background-color:#E9C46A; color:#004D4D; font-size:9px; font-weight:bold; text-transform:uppercase; padding:4px 10px; border-radius:4px;">
                    Confidential Report
                </span>
                <div style="font-size:10px; color:#B2DFDB; margin-top:6px;">
                    Generated on <strong style="color:#ffffff;">{{ $generated_at }}</strong>
                </div>
            </td>
        </tr>
    </table>
</div>
<div style="height:3px; background-color:#005959;"></div>
<div style="height:1px; background-color:#E9C46A;"></div>

{{-- BODY --}}
<div style="padding:14px 22px;">

    {{-- SECTION 1: OVERVIEW STATISTICS --}}
    <h3 style="font-size:13px; color:#008080; margin-bottom:8px; border-bottom: 2px solid #008080; padding-bottom: 3px;">1. OVERVIEW STATISTICS</h3>

    <table width="100%" cellspacing="8" cellpadding="0" style="margin-left: -8px; width: calc(100% + 16px);">
        <tr>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #F59E0B; background-color:#FFFDF7;">
                    <div class="stat-val">{{ $pending_applicants }}</div>
                    <div class="stat-lbl" style="color:#F59E0B;">Pending Applicants</div>
                    <div class="stat-sub">{{ $total_applicants }} total</div>
                </div>
            </td>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #008080; background-color:#F2FCFB;">
                    <div class="stat-val">{{ $total_students }}</div>
                    <div class="stat-lbl" style="color:#008080;">Total Students</div>
                    <div class="stat-sub">{{ $active_students }} active</div>
                </div>
            </td>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #3B82F6; background-color:#F5F9FF;">
                    <div class="stat-val">{{ $total_lecturers }}</div>
                    <div class="stat-lbl" style="color:#3B82F6;">Lecturers</div>
                    <div class="stat-sub">Registered</div>
                </div>
            </td>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #10B981; background-color:#F4FDF9;">
                    <div class="stat-val">{{ $completed_students }}</div>
                    <div class="stat-lbl" style="color:#10B981;">Graduated</div>
                    <div class="stat-sub">Completed</div>
                </div>
            </td>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #EF4444; background-color:#FFF5F5;">
                    <div class="stat-val">{{ $unsupervised_pct }}%</div>
                    <div class="stat-lbl" style="color:#EF4444;">Unsupervised</div>
                    <div class="stat-sub">{{ $unsupervised }} without SV</div>
                </div>
            </td>
            <td width="16%">
                <div class="stat-box" style="border-top:4px solid #EF4444; background-color:#FFF5F5;">
                    <div class="stat-val">{{ $pending_english_pct }}%</div>
                    <div class="stat-lbl" style="color:#EF4444;">Pending English</div>
                    <div class="stat-sub">{{ $pending_english }} students</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- SECTION 2: STUDENT DEMOGRAPHICS (2x2 grid) --}}
    <h3 style="font-size:13px; color:#008080; margin-top:12px; margin-bottom:8px; border-bottom: 2px solid #008080; padding-bottom: 3px;">2. STUDENT DEMOGRAPHICS</h3>

    @php
        $ptotal   = max($phd_students + $master_students, 1);
        $gtotal   = max($male_students + $female_students, 1);
        $payTotal = max($payment_scholarship + $payment_self + $payment_other + $payment_not_stated, 1);

        $payments = [
            ['Scholarship', $payment_scholarship, '#008080'],
            ['Self-funded',  $payment_self,        '#F4A261'],
            ['Other',        $payment_other,       '#9CA3AF'],
            ['Not-stated',   $payment_not_stated,  '#9CA3AF'],
        ];

        $regionColors = [
            'Southeast Asia' => '#2A9D8F',
            'East Asia'      => '#0891B2',
            'South Asia'     => '#7C3AED',
            'Middle East'    => '#E9C46A',
            'Africa'         => '#E76F51',
            'Europe'         => '#3B82F6',
            'Americas'       => '#F4A261',
            'Central Asia' => '#06B6D4',
            'Oceania'        => '#10B981',
            'Other'          => '#9CA3AF',
        ];
        $regionData = [];
        foreach(\App\Models\Student::whereNotNull('country')->get() as $st) {
            $r = \App\Helpers\CountryList::region($st->country);
            $regionData[$r] = ($regionData[$r] ?? 0) + 1;
        }
        arsort($regionData);
        $regionTotal = max(array_sum($regionData), 1);
    @endphp

    {{-- ROW A: Program Type | Gender Distribution --}}
    <table width="100%" cellspacing="8" cellpadding="0" style="margin-left: -8px; width: calc(100% + 16px); margin-bottom:0;">
        <tr>
            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Program Type</div>
                    <table width="100%" cellpadding="2">
                        <tr>
                            <td style="color:#7C3AED; font-weight:bold; font-size:11px;">PhD</td>
                            <td align="right"><b>{{ $phd_students }}</b> ({{ round(($phd_students/$ptotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2"><div class="mtrack"><div class="mfill" style="width:{{ round(($phd_students/$ptotal)*100) }}%; background-color:#7C3AED;"></div></div></td>
                        </tr>
                        <tr>
                            <td style="color:#0891B2; font-weight:bold; font-size:11px;">Master</td>
                            <td align="right"><b>{{ $master_students }}</b> ({{ round(($master_students/$ptotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2"><div class="mtrack" style="margin-bottom:0;"><div class="mfill" style="width:{{ round(($master_students/$ptotal)*100) }}%; background-color:#0891B2;"></div></div></td>
                        </tr>
                    </table>
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Gender Distribution</div>
                    <table width="100%" cellpadding="2">
                        <tr>
                            <td style="color:#3B82F6; font-weight:bold; font-size:11px;">Male</td>
                            <td align="right"><b>{{ $male_students }}</b> ({{ round(($male_students/$gtotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2"><div class="mtrack"><div class="mfill" style="width:{{ round(($male_students/$gtotal)*100) }}%; background-color:#3B82F6;"></div></div></td>
                        </tr>
                        <tr>
                            <td style="color:#EC4899; font-weight:bold; font-size:11px;">Female</td>
                            <td align="right"><b>{{ $female_students }}</b> ({{ round(($female_students/$gtotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2"><div class="mtrack" style="margin-bottom:0;"><div class="mfill" style="width:{{ round(($female_students/$gtotal)*100) }}%; background-color:#EC4899;"></div></div></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- ROW B: Student Origin by Region | Payment Method --}}
    <table width="100%" cellspacing="8" cellpadding="0" style="margin-left: -8px; width: calc(100% + 16px); margin-top:8px;">
        <tr>
            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Student Origin by Region</div>
                    <table width="100%" cellpadding="2">
                        @foreach($regionData as $region => $count)
                        <tr>
                            <td style="color:{{ $regionColors[$region] ?? '#9CA3AF' }}; font-weight:bold; font-size:11px;">{{ $region }}</td>
                            <td align="right" style="font-size:11px;"><b>{{ $count }}</b> ({{ round(($count/$regionTotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="mtrack">
                                    <div class="mfill" style="width:{{ round(($count/$regionTotal)*100) }}%; background-color:{{ $regionColors[$region] ?? '#9CA3AF' }};"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Payment Method</div>
                    <table width="100%" cellpadding="2">
                        @foreach($payments as [$plbl, $pval, $pcol])
                        <tr>
                            <td style="color:{{ $pcol }}; font-weight:bold; font-size:11px;">{{ $plbl }}</td>
                            <td align="right" style="font-size:11px;"><b>{{ $pval }}</b> ({{ round(($pval/$payTotal)*100) }}%)</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="mtrack">
                                    <div class="mfill" style="width:{{ round(($pval/$payTotal)*100) }}%; background-color:{{ $pcol }};"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- SECTION 3: DEEP ANALYTICS --}}
    <h3 style="font-size:13px; color:#008080; margin-top:12px; margin-bottom:8px; border-bottom: 2px solid #008080; padding-bottom: 3px;">3. DEEP ANALYTICS</h3>

    {{-- ROW 1: Status Distribution | Progress Milestones --}}
    <table width="100%" cellspacing="8" cellpadding="0" style="margin-left: -8px; width: calc(100% + 16px); margin-bottom:0;">
        <tr>
            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Status Distribution</div>
                    @php
                        $statuses = [
                            ['Active',     $active_students,     '#10B981'],
                            ['Completed',  $completed_students,  '#008080'],
                            ['Deferred',   $deferred_students,   '#F59E0B'],
                            ['Terminated', $terminated_students, '#EF4444'],
                        ];
                        $maxS = max(array_column($statuses, 1)) ?: 1;
                    @endphp
                    @foreach($statuses as [$lbl, $cnt, $col])
                    <table width="100%" cellpadding="1" style="font-size:11px;">
                        <tr>
                            <td>{{ $lbl }}</td>
                            <td align="right"><b>{{ $cnt }}</b> ({{ $total_students > 0 ? round(($cnt/$total_students)*100) : 0 }}%)</td>
                        </tr>
                    </table>
                    <div class="track"><div class="fill" style="width:{{ round(($cnt/$maxS)*100) }}%; background-color:{{ $col }};"></div></div>
                    @endforeach
                </div>
            </td>

            <td width="50%" valign="top">
                <div class="card">
                    <div class="card-title">Progress Milestones (Active Students Only)</div>
                    @php
                        $milestones = [
                            ['Research Method (RM)', $passed_rm,          '#008080'],
                            ['Proposal Defense (PD)',$passed_pd,          '#008080'],
                            ['Pre-Viva',             $pre_viva_completed, '#008080'],
                            ['Viva',                 $viva_completed,     '#008080'],
                        ];
                    @endphp
                    @foreach($milestones as [$lbl, $cnt, $col])
                    @php $mpct = $active_students > 0 ? round(($cnt/$active_students)*100) : 0; @endphp
                    <table width="100%" cellpadding="1" style="font-size:11px;">
                        <tr>
                            <td><strong style="color:{{ $col }};">{{ $lbl }}</strong></td>
                            <td align="right"><b>{{ $cnt }} / {{ $active_students }}</b> ({{ $mpct }}%)</td>
                        </tr>
                    </table>
                    <div class="track"><div class="fill" style="width:{{ $mpct }}%; background-color:{{ $col }};"></div></div>
                    @endforeach
                </div>
            </td>
        </tr>
    </table>

    {{-- ROW 2: Top Supervisors — FULL WIDTH, 2-column grid --}}
    <div class="card" style="margin-top:8px;">
        <div class="card-title">Top Supervisors (Current Student Supervision)</div>
        @if($top_supervisors->isEmpty())
            <p style="text-align:center; color:#999; padding:8px; font-size:11px;">No supervisors assigned yet</p>
        @else
            @php
                $maxSv     = $top_supervisors->max('main_students_count') ?: 1;
                $halfCount = (int) ceil($top_supervisors->count() / 2);
                $svChunks  = $top_supervisors->chunk($halfCount);
            @endphp
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    @foreach($svChunks as $chunk)
                    <td width="50%" valign="top" style="padding-right:12px;">
                        @foreach($chunk as $sv)
                        <table width="100%" style="margin-bottom:6px;">
                            <tr>
                                <td width="48%" style="font-size:9.5px; color:#2C3E3D;">
                                    {{ \Illuminate\Support\Str::limit($sv->full_name, 28) }}
                                </td>
                                <td width="38%">
                                    <div style="background-color:#E0ECEA; height:9px; border-radius:3px;">
                                        <div style="width:{{ round(($sv->main_students_count/$maxSv)*100) }}%; height:9px; border-radius:3px; background-color:#008080;"></div>
                                    </div>
                                </td>
                                <td width="14%" align="right" style="font-weight:bold; font-size:11px;">{{ $sv->main_students_count }}</td>
                            </tr>
                        </table>
                        @endforeach
                    </td>
                    @endforeach
                </tr>
            </table>
        @endif
    </div>

    {{-- ROW 3: Student Intake Trend — FULL WIDTH --}}
    <div class="card" style="margin-top:8px;">
        <div class="card-title">Student Intake Trend <span style="color:#9BB5B3; font-size:8px; float:right;">(LATEST 15)</span></div>
        @php
            $intakeArr = $intake_data->slice(-15)->values()->toArray();
        @endphp
        <table class="dt">
            <thead>
                <tr>
                    <th>Session</th>
                    <th style="text-align:center; width:50px;">Male</th>
                    <th style="text-align:center; width:55px;">Female</th>
                    <th style="text-align:center; width:50px;">PhD</th>
                    <th style="text-align:center; width:55px;">Master</th>
                    <th style="text-align:center; width:50px;">Total</th>
                    <th style="text-align:center; width:55px;">Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($intakeArr as $i => $row)
                @php
                    $session = $row['intake_session'] ?? 'Not Set';
                    $total   = $row['total'];

                    $male   = \App\Models\Student::where('intake_session', $session)->where('gender', 'Male')->count();
                    $female = \App\Models\Student::where('intake_session', $session)->where('gender', 'Female')->count();
                    $phd    = \App\Models\Student::where('intake_session', $session)->where('program_type', 'PhD')->count();
                    $master = \App\Models\Student::where('intake_session', $session)->where('program_type', 'Master')->count();

                    $prevTotal = isset($intakeArr[$i - 1]) ? $intakeArr[$i - 1]['total'] : null;

                    if ($prevTotal === null || $prevTotal === 0) {
                        $rateText  = '-';
                        $rateColor = '#9BB5B3';
                    } elseif ($total > $prevTotal) {
                        $pct       = round((($total - $prevTotal) / $prevTotal) * 100);
                        $rateText  = '+' . $pct . '%';
                        $rateColor = '#10B981';
                    } elseif ($total < $prevTotal) {
                        $pct       = round((($prevTotal - $total) / $prevTotal) * 100);
                        $rateText  = '-' . $pct . '%';
                        $rateColor = '#EF4444';
                    } else {
                        $rateText  = '0%';
                        $rateColor = '#F59E0B';
                    }
                @endphp
                <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                    <td>{{ $session }}</td>
                    <td style="text-align:center; color:#3B82F6; font-weight:bold;">{{ $male }}</td>
                    <td style="text-align:center; color:#EC4899; font-weight:bold;">{{ $female }}</td>
                    <td style="text-align:center; color:#7C3AED; font-weight:bold;">{{ $phd }}</td>
                    <td style="text-align:center; color:#0891B2; font-weight:bold;">{{ $master }}</td>
                    <td style="text-align:center; font-weight:bold; color:#0D2B28;">{{ $total }}</td>
                    <td style="text-align:center; font-weight:bold; color:{{ $rateColor }};">{{ $rateText }}</td>
                </tr>
                @empty
                <tr><td colspan="7" align="center" style="padding:12px; font-size:11px;">No intake data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- FOOTER --}}
<div class="footer">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="font-size:9px; color:#B2DFDB;">
                UMK PG Tracker &nbsp;&middot;&nbsp; Postgraduate Student Management System &nbsp;&middot;&nbsp; FSDK &nbsp;&middot;&nbsp; Universiti Malaysia Kelantan
            </td>
            <td style="font-size:9px; color:#B2DFDB; text-align:right;">
                <span style="color:#E9C46A; font-weight:bold;">CONFIDENTIAL</span>
            </td>
        </tr>
    </table>
</div>

</body>
</html>