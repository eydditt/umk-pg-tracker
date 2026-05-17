<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use App\Models\Student;
use App\Models\StudentProgress;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $student = $this->record->student;
        if ($student) {
            $data['student_matric_no'] = $student->matric_no;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Approve Button — hanya visible bila Pending
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => $this->record->status === 'Pending')
                ->form([
                    TextInput::make('matric_no')
                        ->label('New Matric Number')
                        ->required()
                        ->placeholder('e.g. A23PM0001')
                        ->rules(['unique:students,matric_no']),
                ])
                ->action(function(array $data) {
                    $record = $this->record;
                    $record->update(['status' => 'Approved']);

                    $student = Student::create([
                        'applicant_id'           => $record->id,
                        'matric_no'              => $data['matric_no'],
                        'program_type'           => $record->program_applied,
                        'email'                  => $record->email,
                        'gender'                 => $record->gender,
                        'nationality_type'       => $record->identity_type === 'IC' ? 'Local' : 'International',
                        'application_docs_links' => $record->application_docs_links,
                        'payment_method'         => 'Not-stated',
                        'status'                 => 'Active',
                    ]);

                    $engStatus = $record->eng_test_taken === 'Taken' ? 'Passed' : 'Pending';

                    StudentProgress::create([
                        'student_id'      => $student->id,
                        'eng_test_status' => $engStatus,
                        'intake_session' => $record->intake_session,
                    ]);

                    Notification::make()
                        ->title('Applicant approved! Student record created.')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // Reject Button — hanya visible bila Pending
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reject Applicant')
                ->modalDescription('This applicant will be marked as Rejected and kept in the system for record purposes.')
                ->visible(fn() => $this->record->status === 'Pending')
                ->action(function() {
                    $this->record->update(['status' => 'Rejected']);

                    Notification::make()
                        ->title('Applicant rejected.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // Delete Button
            Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Permanently Delete Applicant')
                ->modalDescription(new HtmlString(
                        'Are you sure you want to delete this applicant? This action is irreversible, and the record will be permanently removed from the system.<br><br>
                        <strong>⚠️ Note:</strong> If the applicant has already become a student, their student record will also be affected.'
                    ))
                ->visible(fn() => true)
                ->action(function() {
                    if ($this->record->student) {
                        $this->record->student->progress()->delete();
                        $this->record->student->delete();
                    }

                    $this->record->forceDelete();

                    Notification::make()
                        ->title('Applicant and all related records permanently deleted.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}