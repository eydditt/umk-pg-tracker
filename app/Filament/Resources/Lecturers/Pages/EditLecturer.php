<?php

namespace App\Filament\Resources\Lecturers\Pages;

use App\Filament\Resources\Lecturers\LecturerResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditLecturer extends EditRecord
{
    protected static string $resource = LecturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Delete Lecturer')
                ->modalDescription(fn() => new HtmlString(
                    'Are you sure you want to permanently delete <strong>' . e($this->record->full_name) . '</strong>?
                    This action is irreversible.<br><br>
                    <strong>⚠️ Impact on Students:</strong><br>
                    This lecturer is currently supervising <strong>' . $this->record->mainStudents()->count() . ' student(s)</strong>.
                    Deleting this lecturer will <strong>remove them as main supervisor</strong> from all related student records.
                    Affected students will appear as <em>unsupervised</em> and must be reassigned manually.<br><br>
                    <strong>⚠️ Co-supervision:</strong><br>
                    Any co-supervision roles held by this lecturer will also be removed from student records.'
                ))
                ->action(function() {
                    $this->record->mainStudents()->update(['main_sv_id' => null]);

                    if (method_exists($this->record, 'coStudents')) {
                        $this->record->coStudents()->update(['co_sv_id' => null]);
                    }

                    $this->record->forceDelete();

                    Notification::make()
                        ->title('Lecturer permanently deleted. Affected students are now unsupervised.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}