<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Permanently Delete Applicant')
                ->modalDescription('Are you sure? This record will be permanently removed from the system.')
                ->visible(fn() => $this->record->status === 'Approved')
                ->action(function() {
                    $this->record->forceDelete();

                    Notification::make()
                        ->title('Applicant permanently deleted.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}