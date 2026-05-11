<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Applicant Information')
                ->description(fn($record) => $record?->status === 'Approved'
                    ? '⚠️ This applicant has been approved and converted to a student. All fields are locked to maintain data integrity. Please manage this record in the Student module.'
                    : null)
                ->schema([
                    TextInput::make('student_matric_no')
                        ->label('Student Matric No')
                        ->disabled()
                        ->copyable()
                        ->visible(fn($record) => $record?->status === 'Approved'),

                    TextInput::make('full_name')
                        ->label('Full Name')
                        ->required()
                        ->columnSpanFull()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Select::make('gender')
                        ->label('Gender')
                        ->options(['Male' => 'Male', 'Female' => 'Female'])
                        ->required()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Select::make('identity_type')
                        ->label('Identity Type')
                        ->options(['IC' => 'IC (Local)', 'Passport' => 'Passport (International)'])
                        ->required()
                        ->live()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    TextInput::make('identity_no')
                        ->label('Identity Number')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->hint(fn($get, $record) => $record?->status === 'Approved' ? 'Locked' : ($get('identity_type') === 'IC' ? 'Format: 000000000000 (without -)' : null))
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->live()
                        ->disabled(fn($record) => $record?->status === 'Approved'),
                    Select::make('program_applied')
                        ->label('Program Applied')
                        ->options(['Master' => 'Master', 'PhD' => 'PhD'])
                        ->required()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Select::make('intake_session')
                        ->label('Intake Session')
                        ->options(fn() => self::intakeSessionOptions())
                        ->required()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Textarea::make('prev_edu')
                        ->label('Previous Education')
                        ->columnSpanFull()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Select::make('eng_test_taken')
                        ->label('English Test Status')
                        ->options(['Taken' => 'Taken', 'Not Taken' => 'Not Taken'])
                        ->default('Not Taken')
                        ->required()
                        ->live()
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    TextInput::make('eng_test')
                        ->label('MUET/IELTS Score')
                        ->nullable()
                        ->visible(fn($get) => $get('eng_test_taken') === 'Taken')
                        ->disabled(fn($record) => $record?->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                    Select::make('status')
                        ->label('Status Application')
                        ->options(fn($record) => $record?->status === 'Approved'
                            ? ['Pending' => 'Pending', 'Rejected' => 'Rejected', 'Approved' => 'Approved']
                            : ['Pending' => 'Pending', 'Rejected' => 'Rejected'])
                        ->default('Pending')
                        ->required()
                        ->disabled(fn($record) => $record && $record->status === 'Approved')
                        ->hintIcon(fn($record) => $record?->status === 'Approved' ? 'heroicon-o-lock-closed' : null)
                        ->hint(fn($record) => $record?->status === 'Approved' ? 'Locked' : null),
                ])->columns(2),

            Section::make('Application Documents (Google Drive Links)')
                ->description(fn($record) => $record?->status === 'Approved'
                    ? '🔒  Locked — Document management in Student Module (P01: Registration).'
                    : null)
                ->schema([
                    Repeater::make('application_docs_links')
                        ->label('')
                        ->schema([
                            TextInput::make('label')
                                ->label('Document Label')
                                ->required(),
                            TextInput::make('url')
                                ->label('Google Drive URL')
                                ->url()
                                ->required()
                                ->copyable(),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ Add Document')
                        ->defaultItems(0)
                        ->disabled(fn($record) => $record?->status === 'Approved'),
                ]),
        ]);
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