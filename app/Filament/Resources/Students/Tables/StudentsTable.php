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
                    ->label('Matric No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('applicant.full_name')
                    ->label('Student Name')
                    ->searchable(),
                TextColumn::make('program_type')
                    ->label('Program')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'PhD'    => 'phd',
                        'Master' => 'master',
                        default  => 'gray',
                    }),
                TextColumn::make('mainSupervisor.full_name')
                    ->label('Main SV')
                    ->placeholder('No SV Assigned')
                    ->color('warning'),
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
                    ->label('Program')
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
                    DeleteBulkAction::make()
                        ->action(fn($records) => $records->each->forceDelete()),
                ]),
            ]);
    }
}