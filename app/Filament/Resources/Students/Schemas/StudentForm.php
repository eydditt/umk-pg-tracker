<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Lecturer;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ── HEADER ──
            Section::make('Student Profile')->schema([
                TextInput::make('matric_no')
                    ->label('Matric No')
                    ->disabled(),
                TextInput::make('applicant.full_name')
                    ->label('Student Name')
                    ->disabled(),
                TextInput::make('email')
                    ->label('Email Address')
                    ->disabled(),
                Select::make('program_type')
                    ->label('Program')
                    ->options(['Master' => 'Master', 'PhD' => 'PhD'])
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->disabled(),
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'Scholarship' => 'Scholarship',
                        'Self-funded' => 'Self-funded',
                        'Other'       => 'Other',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Student Status')
                    ->options([
                        'Active'     => 'Active',
                        'Completed'  => 'Completed',
                        'Terminated' => 'Terminated',
                        'Deferred'   => 'Deferred',
                    ])
                    ->default('Active')
                    ->required(),
            ])->columns(2),

            // ── TABS ──
            Tabs::make('Progress Phases')->tabs([

                // P01
                Tabs\Tab::make('P01: Registration')->schema([
                    Select::make('progress.eng_test_status')
                        ->label('English Proficiency Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed'])
                        ->required(),
                    Section::make('Application Documents')->schema([
                        Repeater::make('application_docs_links')
                            ->label('')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Document Label')
                                    ->required(),
                                TextInput::make('url')
                                    ->label('Google Drive URL')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Add Document')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),
                    self::gdriveLinkRepeater('P01'),
                ]),

                // P02
                Tabs\Tab::make('P02: Supervision')->schema([
                    Section::make('Supervisor Assignment')->schema([
                        Select::make('main_sv_id')
                            ->label('Main Supervisor')
                            ->options(Lecturer::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Select::make('co_sv_id')
                            ->label('Co-Supervisor')
                            ->options(Lecturer::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),
                    self::gdriveLinkRepeater('P02'),
                ]),

                // P03
                Tabs\Tab::make('P03: Proposal Defense')->schema([
                    Select::make('progress.research_method')
                        ->label('Research Method')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed']),
                    Select::make('progress.pd_status')
                        ->label('Proposal Defense Status')
                        ->options([
                            'Pending'          => 'Pending',
                            'Passed'           => 'Passed',
                            'Minor Correction' => 'Minor Correction',
                            'Major Correction' => 'Major Correction',
                        ]),
                    self::gdriveLinkRepeater('P03'),
                ]),

                // P04
                Tabs\Tab::make('P04: Thesis')->schema([
                    self::gdriveLinkRepeater('P04'),
                ]),

                // P05
                Tabs\Tab::make('P05: Viva')->schema([
                    Select::make('progress.pre_viva_status')
                        ->label('Pre-Viva Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed']),
                    Select::make('progress.viva_status')
                        ->label('Viva Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed']),
                    self::gdriveLinkRepeater('P05'),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    protected static function gdriveLinkRepeater(string $phase): Repeater
    {
        return Repeater::make('progress.gdrive_links')
            ->label('Google Drive Documents — Phase ' . $phase)
            ->schema([
                TextInput::make('label')
                    ->label('Document Label')
                    ->required(),
                TextInput::make('url')
                    ->label('Google Drive URL')
                    ->url()
                    ->required(),
                TextInput::make('phase')
                    ->default($phase)
                    ->hidden(),
            ])
            ->columns(2)
            ->addActionLabel('+ Add Link')
            ->defaultItems(0)
            ->columnSpanFull();
    }
}