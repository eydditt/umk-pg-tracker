<?php

namespace App\Filament\Resources\Lecturers\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LecturersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('staff_no')
                    ->label('Staff No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('active_students_count')
                    ->label('Active Students')
                    ->sortable()
                    ->badge(),
                  TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Lecturer')
                    ->modalDescription(fn($record) => new HtmlString(
                        'Are you sure you want to permanently delete <strong>' . e($record->full_name) . '</strong>?
                        This action is irreversible.<br><br>
                        <strong>⚠️ Impact on Students:</strong><br>
                        This lecturer is currently supervising <strong>' . $record->mainStudents()->count() . ' student(s)</strong>.
                        Deleting this lecturer will <strong>remove them as main supervisor</strong> from all related student records.
                        Affected students will appear as <em>unsupervised</em> and will need to be reassigned manually.<br><br>
                        <strong>⚠️ Co-supervision:</strong><br>
                        Any co-supervision roles held by this lecturer will also be removed from student records.'
                    ))
                    ->action(function($record) {
                        $record->mainStudents->each->update(['main_sv_id' => null]);

                        if (method_exists($record, 'coStudents')) {
                            $record->coStudents->each->update(['co_sv_id' => null]);
                        }

                        $record->forceDelete();

                        Notification::make()
                            ->title('Lecturer deleted. Affected students are now unsupervised.')
                            ->warning()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these lecturers? All students supervised by them will automatically become unsupervised. This will be recorded in the Activity Log.')
                        ->action(function($records) {
                            $records->each(function ($record) {
                                $record->mainStudents->each->update(['main_sv_id' => null]);
                                
                                if (method_exists($record, 'coStudents')) {
                                    $record->coStudents->each->update(['co_sv_id' => null]);
                                }
                                
                                $record->forceDelete();
                            });

                            Notification::make()
                                ->title('Selected lecturers deleted securely.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}