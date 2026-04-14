<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('applicant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('matric_no')
                    ->required(),
                Select::make('program_type')
                    ->options(['Master' => 'Master', 'PhD' => 'Ph d'])
                    ->required(),
                Select::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->required(),
                Select::make('payment_method')
                    ->options(['Scholarship' => 'Scholarship', 'Self-funded' => 'Self funded', 'Other' => 'Other'])
                    ->required(),
                TextInput::make('main_sv_id')
                    ->numeric(),
                TextInput::make('co_sv_id')
                    ->numeric(),
                Select::make('status')
                    ->options([
            'Active' => 'Active',
            'Completed' => 'Completed',
            'Terminated' => 'Terminated',
            'Deferred' => 'Deferred',
        ])
                    ->default('Active')
                    ->required(),
            ]);
    }
}
