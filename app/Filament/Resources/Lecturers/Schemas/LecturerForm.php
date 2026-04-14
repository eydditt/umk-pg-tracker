<?php

namespace App\Filament\Resources\Lecturers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('staff_no')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
            ]);
    }
}
