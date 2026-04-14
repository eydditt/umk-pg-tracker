<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                Select::make('identity_type')
                    ->options(['IC' => 'I c', 'Passport' => 'Passport'])
                    ->required(),
                TextInput::make('identity_no')
                    ->required(),
                Select::make('program_applied')
                    ->options(['Master' => 'Master', 'PhD' => 'Ph d'])
                    ->required(),
                Textarea::make('prev_edu')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('eng_test'),
                Select::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'])
                    ->default('Pending')
                    ->required(),
                TextInput::make('application_docs_links'),
            ]);
    }
}
