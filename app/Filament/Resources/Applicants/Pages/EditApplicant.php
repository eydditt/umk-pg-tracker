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
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => $this->record->status === 'Pending')
                ->requiresConfirmation()
                ->modalHeading('Approve Applicant')
                ->modalDescription(new HtmlString('
                    <div style="padding:12px;background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;margin-bottom:12px;">
                        <strong style="color:#92400e;">⚠️ Important — Before You Approve:</strong>
                        <ul style="margin-top:8px;color:#92400e;padding-left:18px;line-height:1.8;">
                            <li>Make sure you have <strong>saved all changes</strong> to this form first.</li>
                            <li>Unsaved changes will <strong>NOT be carried over</strong> to the student record.</li>
                            <li>Click <strong>Cancel</strong>, save the form, then approve again if needed.</li>
                        </ul>
                    </div>
                    <div style="padding:12px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;">
                        <strong style="color:#166534;">✅ After confirming:</strong>
                        <ul style="margin-top:8px;color:#166534;padding-left:18px;line-height:1.8;">
                            <li>Applicant status → <strong>Approved</strong></li>
                            <li>Student record will be <strong>automatically created</strong></li>
                            <li>All saved fields will be synced to student profile</li>
                        </ul>
                    </div>
                '))
                ->modalSubmitActionLabel('Yes, I have saved — Approve Now')
                ->modalCancelActionLabel('Cancel — Let me save first')
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
                    'country'                => $record->country,
                    'application_docs_links' => $record->application_docs_links,
                    'payment_method'         => 'Not-stated',
                    'status'                 => 'Active',
                    'intake_session'         => $record->intake_session,
                    'intake_month'           => $record->intake_month ?? 'September',
                ]);

                        $engStatus = match($record->eng_test_taken) {
                            'Taken'        => 'Passed',
                            'Not Required' => 'Not Required',
                            default        => 'Pending',
                            };

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