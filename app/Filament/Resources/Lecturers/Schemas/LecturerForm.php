<?php

namespace App\Filament\Resources\Lecturers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lecturer Information')->schema([
                TextInput::make('staff_no')
                    ->label('Staff No')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),
            ])->columns(2),
        ]);
    }
}