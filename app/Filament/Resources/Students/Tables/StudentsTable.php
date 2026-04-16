<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matric_no')
                    ->label('Nombor Matrik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('applicant.full_name')
                    ->label('Nama Pelajar')
                    ->searchable(),
                TextColumn::make('program_type')
                    ->label('Program')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'PhD'    => 'danger',
                        'Master' => 'info',
                        default  => 'gray',
                    }),
                TextColumn::make('mainSupervisor.full_name')
                    ->label('SV Utama')
                    ->placeholder('Tiada SV')
                    ->color('danger'),
                TextColumn::make('progress.eng_test_status')
                    ->label('English')
                    ->badge()
                    ->color(fn($state) => $state === 'Passed' ? 'success' : 'warning'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'Active'     => 'success',
                        'Completed'  => 'info',
                        'Terminated' => 'danger',
                        'Deferred'   => 'warning',
                        default      => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('program_type')
                    ->options(['Master' => 'Master', 'PhD' => 'PhD']),
                SelectFilter::make('status')
                    ->options([
                        'Active'     => 'Active',
                        'Completed'  => 'Completed',
                        'Terminated' => 'Terminated',
                        'Deferred'   => 'Deferred',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}