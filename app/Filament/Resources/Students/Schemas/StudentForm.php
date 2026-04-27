<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Lecturer;
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
                ->description('⚠️ Some fields are locked to maintain data integrity. To make changes to locked fields, please delete the student and applicant records.')
                ->schema([
                    TextInput::make('matric_no')
                        ->label('Matric No')
                        ->disabled()
                        ->hintIcon('heroicon-o-lock-closed')
                        ->hint('Locked'),
                    TextInput::make('applicant.full_name')
                        ->label('Student Name')
                        ->disabled()
                        ->hintIcon('heroicon-o-lock-closed')
                        ->hint('Locked'),
                    TextInput::make('applicant.identity_type')
                        ->label('Identity Type')
                        ->disabled()
                        ->hintIcon('heroicon-o-lock-closed')
                        ->hint('Locked'),
                    TextInput::make('applicant.identity_no')
                        ->label('Identity Number')
                        ->disabled()
                        ->hintIcon('heroicon-o-lock-closed')
                        ->hint('Locked'),
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
                    Textarea::make('applicant_prev_edu')
                        ->label('Previous Education')
                        ->dehydrated(true)
                        ->columnSpanFull(),
                    Select::make('program_type')
                        ->label('Program')
                        ->options(['Master' => 'Master', 'PhD' => 'PhD'])
                        ->required()
                        ->dehydrated(true),
                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'Scholarship' => 'Scholarship',
                            'Self-funded'  => 'Self-funded',
                            'Other'        => 'Other',
                        ])
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
                ])->columns(2)->columnSpanFull(),

            Tabs::make('Progress Phases')->tabs([
                

                Tabs\Tab::make('P01: Registration')->schema([

                Section::make('Application Documents (from Applicant)')
                        ->description('Documents submitted during application.')
                        ->schema([
                            Repeater::make('application_docs_links')
                                ->label('')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Document Label')
                                        ->disabled(),
                                    TextInput::make('url')
                                        ->label('Google Drive URL')
                                        ->disabled()
                                        ->copyable(),
                                ])
                                ->columns(2)
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->columnSpanFull(),
                        ]),

                    Select::make('progress.eng_test_status')
                        ->label('English Proficiency Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed'])
                        ->required()
                        ->dehydrated(true)
                        ->live(),
                    TextInput::make('eng_test')
                        ->label('MUET/IELTS Score')
                        ->dehydrated(true),
                    Repeater::make('gdrive_p01')
                        ->label('Google Drive Documents — Phase P01')
                        ->schema([
                            TextInput::make('label')->label('Document Label')->required(),
                            TextInput::make('url')->label('Google Drive URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Link for P01')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('P02: Supervision')->schema([
                    Section::make('Supervisor Assignment')->schema([
                        Select::make('main_sv_id')
                            ->label('Main Supervisor')
                            ->options(Lecturer::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->dehydrated(true)
                            ->nullable(),
                        Select::make('co_sv_id')
                            ->label('Co-Supervisor')
                            ->options(Lecturer::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->dehydrated(true)
                            ->nullable(),
                    ])->columns(2),
                    Repeater::make('gdrive_p02')
                        ->label('Google Drive Documents — Phase P02')
                        ->schema([
                            TextInput::make('label')->label('Document Label')->required(),
                            TextInput::make('url')->label('Google Drive URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Link for P02')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('P03: Proposal Defense')->schema([
                    Select::make('progress.research_method')
                        ->label('Research Method')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed'])
                        ->dehydrated(true),
                    Select::make('progress.pd_status')
                        ->label('Proposal Defense Status')
                        ->options([
                            'Pending'          => 'Pending',
                            'Passed'           => 'Passed',
                            'Minor Correction' => 'Minor Correction',
                            'Major Correction' => 'Major Correction',
                        ])
                        ->dehydrated(true),
                    Repeater::make('gdrive_p03')
                        ->label('Google Drive Documents — Phase P03')
                        ->schema([
                            TextInput::make('label')->label('Document Label')->required(),
                            TextInput::make('url')->label('Google Drive URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Link for P03')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('P04: Thesis')->schema([
                    Repeater::make('gdrive_p04')
                        ->label('Google Drive Documents — Phase P04')
                        ->schema([
                            TextInput::make('label')->label('Document Label')->required(),
                            TextInput::make('url')->label('Google Drive URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Link for P04')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('P05: Viva')->schema([
                    Select::make('progress.pre_viva_status')
                        ->label('Pre-Viva Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed'])
                        ->dehydrated(true),
                    Select::make('progress.viva_status')
                        ->label('Viva Status')
                        ->options(['Pending' => 'Pending', 'Passed' => 'Passed', 'Failed' => 'Failed'])
                        ->dehydrated(true),
                    Repeater::make('gdrive_p05')
                        ->label('Google Drive Documents — Phase P05')
                        ->schema([
                            TextInput::make('label')->label('Document Label')->required(),
                            TextInput::make('url')->label('Google Drive URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Link for P05')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

            ])->columnSpanFull(),
        ]);
    }
}