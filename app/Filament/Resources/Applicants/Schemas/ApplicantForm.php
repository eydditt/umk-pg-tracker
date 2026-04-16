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
            Section::make('Maklumat Pemohon')->schema([
                TextInput::make('full_name')->required()->columnSpanFull(),
                Select::make('identity_type')
                    ->options(['IC' => 'IC (Lokal)', 'Passport' => 'Passport (Antarabangsa)'])
                    ->required(),
                TextInput::make('identity_no')->required()->unique(ignoreRecord: true),
                Select::make('program_applied')
                    ->options(['Master' => 'Master', 'PhD' => 'PhD'])->required(),
                Textarea::make('prev_edu')->label('Pendidikan Lepas')->columnSpanFull(),
                TextInput::make('eng_test')->label('MUET/IELTS Score')->nullable(),
                Select::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'])
                    ->default('Pending')->required(),
            ])->columns(2),

            Section::make('Dokumen Permohonan (Pautan Google Drive)')->schema([
                Repeater::make('application_docs_links')
                    ->label('')
                    ->schema([
                        TextInput::make('label')->label('Label Dokumen')->required(),
                        TextInput::make('url')->label('URL Google Drive')->url()->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('+ Tambah Dokumen')
                    ->defaultItems(0),
            ]),
        ]);
    }
}