<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('matric_no')
                    ->label('No. Matrik')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('name')
                    ->label('Nama Penuh')
                    ->required()
                    ->maxLength(255),
                
               
                TextInput::make('email')
                    ->label('Alamat E-mel')
                    ->email() 
                    ->required()
                    ->maxLength(255),
            
                
                Select::make('program')
                    ->label('Program Pengajian')
                    ->options([
                        'Master' => 'Master',
                        'PhD' => 'PhD',
                    ])
                    ->required(),
                
                TextInput::make('faculty')
                    ->label('Fakulti')
                    ->required()
                    ->maxLength(255),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Aktif',
                        'Graduated' => 'Telah Bergraduat',
                        'Terminated' => 'Diberhentikan',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }
}