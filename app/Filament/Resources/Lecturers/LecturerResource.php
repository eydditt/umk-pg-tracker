<?php

namespace App\Filament\Resources\Lecturers;

use App\Filament\Resources\Lecturers\Pages\CreateLecturer;
use App\Filament\Resources\Lecturers\Pages\EditLecturer;
use App\Filament\Resources\Lecturers\Pages\ListLecturers;
use App\Filament\Resources\Lecturers\Schemas\LecturerForm;
use App\Filament\Resources\Lecturers\Tables\LecturersTable;
use App\Models\Lecturer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return LecturerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LecturersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLecturers::route('/'),
            'create' => CreateLecturer::route('/create'),
            'edit' => EditLecturer::route('/{record}/edit'),
        ];
    }
}
