<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Lecturer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Student Profile')
                ->description('⚠️ Locked fields maintain data integrity. To change locked fields, delete and re-register the student.')
                ->schema([
                    TextInput::make('matric_no')
                        ->label('Matric No')
                        ->disabled()
                        ->hint('Locked')->hintIcon('heroicon-o-lock-closed'),
                    TextInput::make('applicant.full_name')
                        ->label('Student Name')
                        ->disabled()
                        ->hint('Locked')->hintIcon('heroicon-o-lock-closed'),
                    TextInput::make('applicant.identity_type')
                        ->label('Identity Type')
                        ->disabled()
                        ->hint('Locked')->hintIcon('heroicon-o-lock-closed'),
                    TextInput::make('applicant.identity_no')
                        ->label('Identity Number')
                        ->disabled()
                        ->hint('Locked')->hintIcon('heroicon-o-lock-closed'),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->dehydrated(true)
                        ->hint('Editable'),
                    Select::make('gender')
                        ->label('Gender')
                        ->options(['Male' => 'Male', 'Female' => 'Female'])
                        ->required()
                        ->dehydrated(true)
                        ->hint('Editable'),
                    Select::make('country')
                        ->label('Country')
                        ->options(\App\Helpers\CountryList::options())
                        ->searchable()
                        ->dehydrated(true),
                    Select::make('program_type')
                        ->label('Program')
                        ->options(['Master' => 'Master', 'PhD' => 'PhD'])
                        ->required()
                        ->dehydrated(true),
                    Select::make('intake_session')
                        ->label('Intake Session')
                        ->options(fn() => self::intakeSessionOptions())
                        ->required()
                        ->dehydrated(true),

                    Select::make('intake_month')
                        ->label('Intake Month')
                        ->options([
                            'September' => '🎓 September',
                            'February'  => '🎓 February',
                        ])
                        ->default('September')
                        ->required()
                        ->dehydrated(true),                        
                    Select::make('status')
                        ->label('Student Status')
                        ->options([
                            'Active'     => 'Active',
                            'Completed'  => 'Completed',
                            'Terminated' => 'Terminated',
                            'Deferred'   => 'Deferred',
                        ])
                        ->default('Active')
                        ->required()
                        ->dehydrated(true),
                    Textarea::make('applicant_prev_edu')
                        ->label('Previous Education')
                        ->dehydrated(true)
                        ->columnSpanFull(),
                ])->columns(3)->columnSpanFull(),

            Tabs::make('Progress Phases')->tabs([

                Tabs\Tab::make('P01: Registration')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Faculty-level recruitment documents required for registration.')
                        ->schema([self::infoBox([
                            'Borang Permohonan Kemasukan Pelajar Pascasiswazah',
                            'Salinan Sijil Akademik Lepas (Disahkan)',
                            'Salinan Transkrip Akademik (Disahkan)',
                            'Salinan IC / Passport',
                            'Gambar Passport Terbaru',
                            'Surat Sokongan / Pengesahan Majikan (jika berkaitan)',
                        ])])->collapsible()->collapsed(),

                    Section::make('📁 Application Documents (from Applicant)')
                        ->description('Documents submitted during application. Read-only — manage in Applicant module.')
                        ->schema([
                            Repeater::make('application_docs_links')
                                ->label('')
                                ->schema([
                                    TextInput::make('label')->label('Document Label')->disabled(),
                                    TextInput::make('url')->label('URL')->disabled()->copyable(),
                                ])
                                ->columns(2)
                                ->addable(false)->deletable(false)->reorderable(false)
                                ->columnSpanFull(),
                        ])->collapsible()->collapsed(),

                    Section::make('English Proficiency')->schema([
                       Select::make('progress.eng_test_status')
                                ->label('Status')
                                ->options([
                                    'Pending'      => 'Pending',
                                    'Passed'       => 'Passed',
                                    'Not Required' => 'Not Required',
                                ])
                                ->required()->dehydrated(true)->live(),
                        TextInput::make('eng_test')
                            ->label('MUET/IELTS Score')
                            ->dehydrated(true),
                    ])->columns(2),

                    self::gdriveRepeater('gdrive_p01', 'P01'),
                ]),

                Tabs\Tab::make('P02: Admission & Registration')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Program registration, intake documents, and official transfer approvals.')
                        ->schema([self::infoBox([
                            'Borang Kemasukan Program',
                            'Surat Tawaran Rasmi',
                            'Borang Pertukaran Program (jika ada)',
                            'Slip Pendaftaran Semester',
                        ])])->collapsible()->collapsed(),

                    // ── SEMESTER STATUS SECTION ──
                    Section::make('📅 Semester Status')
                        ->schema([
                            \Filament\Forms\Components\Placeholder::make('semester_info')
                                ->label('')
                                ->content(function ($record) {
                                    if (!$record) return '—';

                                    $current = $record->current_semester;
                                    $max     = $record->effective_max_semester;
                                    $base    = $record->max_semester;
                                    $extra   = $record->extended_semesters;
                                    $status  = $record->extension_status;

                                    if (!$current) return new \Illuminate\Support\HtmlString(
                                        '<span style="color:#9ca3af;">Semester cannot be calculated — no intake session set.</span>'
                                    );

                                    $pct   = min(100, round(($current / $max) * 100));
                                    $color = $current > $max ? '#ef4444' : ($current >= $max ? '#f59e0b' : '#2A9D8F');
                                    $bar   = '<div style="background:#e5e7eb;border-radius:6px;height:10px;margin:6px 0 10px;">
                                                <div style="width:'.$pct.'%;background:'.$color.';height:10px;border-radius:6px;transition:width 0.3s;"></div>
                                            </div>';

                                    $extInfo = $extra > 0
                                        ? '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:11px;margin-left:8px;">+' . $extra . ' sem extended</span>'
                                        : '';

                                    $statusBadge = match($status) {
                                        'Pending'  => '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:11px;">⏳ Extension Pending</span>',
                                        'Approved' => '<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:4px;font-size:11px;">✅ Extension Approved</span>',
                                        'Rejected' => '<span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:4px;font-size:11px;">❌ Extension Rejected</span>',
                                        default    => '',
                                    };

                                    $exceeded = $current > $max
                                        ? '<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-top:10px;color:#991b1b;font-weight:600;">
                                            ⚠️ This student has exceeded the maximum semester limit (' . $max . ' semesters). An extension request is required.
                                        </div>'
                                        : '';

                                    $approaching = ($current == $max && $current <= $max)
                                        ? '<div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px 14px;margin-top:10px;color:#92400e;font-weight:600;">
                                            ⚠️ This student is on their final semester. Consider submitting an extension request soon.
                                        </div>'
                                        : '';

                                    return new \Illuminate\Support\HtmlString('
                                        <div style="font-size:13px;">
                                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                                <strong>Current Semester:</strong>
                                                <span style="font-size:18px;font-weight:700;color:'.$color.';">Sem '.$current.'</span>
                                                <span style="color:#9ca3af;">/ '.$max.' max</span>
                                                '.$extInfo.'
                                                '.$statusBadge.'
                                            </div>
                                            '.$bar.'
                                            <div style="color:#6b7280;font-size:12px;">
                                                Program: <strong>'.$record->program_type.'</strong> &nbsp;|&nbsp;
                                                Intake: <strong>'.$record->intake_session.'</strong> &nbsp;|&nbsp;
                                                Base limit: <strong>'.$base.' semesters</strong>
                                            </div>
                                            '.$exceeded.'
                                            '.$approaching.'
                                        </div>
                                    ');
                                }),
                        ]),

                    // ── EXTENSION REQUEST SECTION ──
                    Section::make('📝 Semester Extension Request')
                        ->description('Submit an extension request if the student requires additional semesters beyond the standard limit.')
                        ->schema([

                            \Filament\Forms\Components\TextInput::make('semester_override')
                            ->label('Semester Override')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->nullable()
                            ->dehydrated(true)
                            ->hint('Optional — overrides auto-calculation')
                            ->helperText('Leave blank to use auto-calculated semester'),

                            \Filament\Forms\Components\Select::make('extension_status')
                                ->label('Extension Status')
                                ->options([
                                    'None'     => 'None',
                                    'Pending'  => 'Pending',
                                    'Approved' => 'Approved',
                                    'Rejected' => 'Rejected',
                                ])
                                ->default('None')
                                ->dehydrated(true)
                                ->live(),

                            \Filament\Forms\Components\TextInput::make('extended_semesters')
                                ->label('Additional Semesters Granted')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(6)
                                ->dehydrated(true)
                                ->visible(fn($get) => $get('extension_status') === 'Approved')
                                ->helperText('Max 6 additional semesters allowed'),

                            \Filament\Forms\Components\Textarea::make('extension_reason')
                                ->label('Reason for Extension')
                                ->rows(3)
                                ->dehydrated(true)
                                ->visible(fn($get) => in_array($get('extension_status'), ['Pending', 'Approved', 'Rejected']))
                                ->placeholder('State the reason for semester extension request...'),
                        ])->columns(2),

                    self::gdriveRepeater('gdrive_p02', 'P02'),
                ]),

                Tabs\Tab::make('P03: Supervisor Appointment')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Official selection and appointment of supervisors.')
                        ->schema([self::infoBox([
                            'Borang Pelantikan Penyelia Utama',
                            'Borang Pelantikan Penyelia Bersama (jika ada)',
                            'Surat Persetujuan Penyelia',
                        ])])->collapsible()->collapsed(),

                    Section::make('Supervisor Assignment')->schema([
                        Select::make('main_sv_id')
                            ->label('Main Supervisor')
                            ->options(function() {
                                return Lecturer::withCount(['mainStudents' => fn($q) => $q->where('status', 'Active')])
                                    ->get()
                                    ->mapWithKeys(fn($l) => [
                                        $l->id => $l->full_name . ' (' . $l->main_students_count . ' active students)'
                                    ]);
                            })
                            ->searchable()
                            ->dehydrated(true)
                            ->nullable(),

                        \Filament\Forms\Components\Repeater::make('co_supervisor_ids')
                        ->label('Co-Supervisors')
                        ->schema([
                            Select::make('lecturer_id')
                                ->label('Co-Supervisor')
                                ->options(Lecturer::all()->pluck('full_name', 'id'))
                                ->searchable()
                                ->required()
                                ->distinct(),
                        ])
                        ->addActionLabel('+ Add Co-Supervisor')
                        ->defaultItems(0)
                        ->maxItems(5)
                        ->columnSpanFull()
                        ->dehydrated(true),
                ])->columns(2),

                    self::gdriveRepeater('gdrive_p03', 'P03'),
                ]),

                Tabs\Tab::make('P04: Thesis Evaluation')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Thesis preparation, proofreading, and formal submission for examination.')
                        ->schema([self::infoBox([
                            'Notis Penghantaran Tesis',
                            'Laporan Pruf Baca (Proofreading)',
                            'Borang Pelantikan Pemeriksa',
                            'Salinan Tesis (Soft Copy)',
                        ])])->collapsible()->collapsed(),

                    Section::make('Thesis Progress')->schema([
                        Select::make('progress.research_method')
                            ->label('Research Method')
                            ->options(['Pending' => 'Pending', 'Passed' => 'Passed'])
                            ->dehydrated(true),
                        Select::make('progress.pd_status')
                            ->label('Proposal Defense')
                            ->options([
                                'Pending'          => 'Pending',
                                'Passed'           => 'Passed',
                                'Minor Correction' => 'Minor Correction',
                                'Major Correction' => 'Major Correction',
                            ])->dehydrated(true),
                        Select::make('progress.pre_viva_status')
                            ->label('Pre-Viva')
                            ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed'])
                            ->dehydrated(true),
                        Select::make('progress.viva_status')
                            ->label('Viva')
                            ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed'])
                            ->dehydrated(true),
                    ])->columns(2),

                    self::gdriveRepeater('gdrive_p04', 'P04'),
                ]),

                Tabs\Tab::make('P05: Scholarship & Fees')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Tuition fee payments, scholarships, and financial assistance records.')
                        ->schema([self::infoBox([
                            'Resit Bayaran Yuran Pengajian',
                            'Surat Tawaran Biasiswa',
                            'Borang Permohonan Bantuan Kewangan',
                            'Penyata Akaun Pembayaran',
                        ])])->collapsible()->collapsed(),

                    Section::make('Financial Status')->schema([
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'Not-stated' => 'Not-stated',
                                    'Scholarship' => 'Scholarship',
                                    'Self-funded'  => 'Self-funded',
                                    'Other'        => 'Other',
                                ])
                                ->required()
                                ->dehydrated(true),
                            Select::make('progress.scholarship_status')
                                ->label('Scholarship Status')
                                ->options([
                                    'Not Applicable' => 'Not Applicable',
                                    'Pending'        => 'Pending',
                                    'Approved'       => 'Approved',
                                    'Rejected'       => 'Rejected',
                                ])->dehydrated(true),
                        ])->columns(2),

                    self::gdriveRepeater('gdrive_p05', 'P05'),
                ]),

                Tabs\Tab::make('P06: Academic Progress')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Research milestones, progress reports, and monitoring assessments.')
                        ->schema([self::infoBox([
                            'Laporan Kemajuan Penyelidikan (Semesterly)',
                            'Borang Penilaian Penyelia',
                            'Minit Mesyuarat Penyelia-Pelajar',
                            'Log Aktiviti Penyelidikan',
                        ])])->collapsible()->collapsed(),

                    Section::make('Progress Monitoring')->schema([
                        Select::make('progress.progress_report_status')
                            ->label('Progress Report Status')
                            ->options([
                                'Pending'   => 'Pending',
                                'Submitted' => 'Submitted',
                                'Approved'  => 'Approved',
                                'Rejected'  => 'Rejected',
                            ])->dehydrated(true),
                        DatePicker::make('progress.last_progress_report_date')
                            ->label('Last Report Date')
                            ->dehydrated(true),
                    ])->columns(2),

                    self::gdriveRepeater('gdrive_p06', 'P06'),
                ]),

                Tabs\Tab::make('P07: Award & Graduation')->schema([
                    Section::make('📋 Required Documents')
                        ->description('Final stage documentation for academic award verification and official degree conferral (Master & PhD).')
                        ->schema([self::infoBox([
                            'Borang Permohonan Konvokesyen',
                            'Surat Kelulusan Senat / Jawatankuasa Pengajian Siswazah',
                            'Transkrip Akademik Akhir (Disahkan)',
                            'Sijil Ijazah Sarjana / Doktor Falsafah',
                            'Borang Pengesahan MQA (Malaysian Qualifications Agency)',
                            'Laporan Pembetulan Tesis Akhir (jika ada)',
                            'Borang Pengesahan Penyerahan Tesis Akhir',
                        ])])->collapsible()->collapsed(),

                    Section::make('Award Status')->schema([
                        Select::make('progress.degree_verification_status')
                            ->label('Verification Status')
                            ->options([
                                'Pending'     => 'Pending',
                                'In Progress' => 'In Progress',
                                'Verified'    => 'Verified',
                                'Awarded'     => 'Awarded',
                            ])->dehydrated(true),
                        DatePicker::make('progress.graduation_date')
                            ->label('Graduation Date')
                            ->dehydrated(true),
                    ])->columns(2),

                    self::gdriveRepeater('gdrive_p07', 'P07'),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    protected static function gdriveRepeater(string $field, string $phase): Repeater
    {
        return Repeater::make($field)
            ->label('Google Drive Documents — ' . $phase)
            ->schema([
                TextInput::make('label')->label('Document Label')->required(),
                TextInput::make('url')->label('Google Drive URL')->url()->required()->copyable(),
            ])
            ->columns(2)
            ->addActionLabel('+ Add Link for ' . $phase)
            ->defaultItems(0)
            ->columnSpanFull();
    }

    protected static function infoBox(array $items): Placeholder
    {
        $list = collect($items)->map(fn($item) => "• {$item}")->implode("\n");
        return Placeholder::make('required_docs_info')
            ->label('')
            ->content($list);
    }

    protected static function intakeSessionOptions(): array
        {
            $options = [];
            $startYear = 2020;
            $endYear = now()->year + 5;

            for ($year = $startYear; $year <= $endYear; $year++) {
                $next = $year + 1;
                $key = "{$year}/{$next}";
                $options[$key] = $key;
            }

            return $options;
        }
}