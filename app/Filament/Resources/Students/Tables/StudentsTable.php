<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                \Filament\Actions\Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Permanently Delete Student')
                    ->modalDescription(new HtmlString(
                        'Are you sure you want to delete this student? This action is irreversible and the student record will be permanently removed from the system.<br><br>
                        <strong>⚠️ Note:</strong> The applicant record linked to this student will remain in the system for reference purposes. Only the student and progress data will be deleted.'
                    ))
                    ->modalSubmitActionLabel('Yes, Delete')
                    ->action(function($record) {
                        $record->progress()->delete();
                        $record->forceDelete();

                        \Filament\Notifications\Notification::make()
                            ->title('Student permanently deleted.')
                            ->warning()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Permanently Delete Selected Students')
                        ->modalDescription(new HtmlString(
                            'Are you sure you want to delete the selected students? This action is irreversible and all student records will be permanently removed from the system.<br><br>
                            <strong>⚠️ Note:</strong> The applicant record linked to this student will remain in the system for reference purposes. Only the student and progress data will be deleted.'
                        ))
                        ->modalSubmitActionLabel('Yes, Delete All')
                        ->action(fn($records) => $records->each->forceDelete()),
                ]),
            ]);
    }
}