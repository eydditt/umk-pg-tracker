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
            Section::make('Applicant Information')->schema([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->columnSpanFull(),
                Select::make('identity_type')
                    ->label('Identity Type')
                    ->options(['IC' => 'IC (Local)', 'Passport' => 'Passport (International)'])
                    ->required(),
                TextInput::make('identity_no')
                    ->label('Identity Number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->hint(fn($get) => $get('identity_type') === 'IC' ? '(without -)' : null)
                    ->live(),
                Select::make('program_applied')
                    ->label('Program Applied')
                    ->options(['Master' => 'Master', 'PhD' => 'PhD'])
                    ->required(),
                Textarea::make('prev_edu')
                    ->label('Previous Education')
                    ->columnSpanFull(),
                TextInput::make('eng_test')
                    ->label('MUET/IELTS Score')
                    ->nullable(),
                Select::make('status')
                    ->label('Status')
                    ->options(['Pending' => 'Pending', 'Rejected' => 'Rejected'])
                    ->default('Pending')
                    ->required()
                    ->disabled(fn($record) => $record && $record->status === 'Approved'),
                    ])->columns(2),

            Section::make('Application Documents (Google Drive Links)')->schema([
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
                    ->defaultItems(0),
            ]),
        ]);
    }
}