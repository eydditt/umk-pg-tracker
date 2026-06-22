<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->label('Delete Student')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Permanently Delete Student')
                ->modalDescription(new HtmlString(
                    'Are you sure you want to delete this student? This action is irreversible and the student record will be permanently removed from the system.<br><br>
                    <strong>⚠️ Note:</strong> The applicant record linked to this student will remain in the system for reference purposes. Only the student and progress data will be deleted.'
                ))
                ->modalSubmitActionLabel('Yes, Delete')
                ->action(function() {
                    $this->record->progress()->delete();
                    $this->record->forceDelete();

                    Notification::make()
                        ->title('Student permanently deleted.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $student = $this->record->load(['applicant', 'progress']);

        if ($student->applicant) {
            $data['applicant']['full_name']     = $student->applicant->full_name;
            $data['applicant']['identity_type'] = $student->applicant->identity_type;
            $data['applicant']['identity_no']   = $student->applicant->identity_no;
            $data['email']                      = $student->email ?? $student->applicant->email;
            $data['eng_test']                   = $student->applicant->eng_test;
            $data['applicant_prev_edu']         = $student->applicant->prev_edu;
            $data['application_docs_links']     = $student->application_docs_links ?? [];
            $data['intake_month'] = $student->intake_month ?? 'September';
            $data['extended_semesters'] = $student->extended_semesters ?? 0;
            $data['extension_reason']   = $student->extension_reason;
            $data['extension_status']   = $student->extension_status ?? 'None';
        }
 // Load co-supervisors
        $data['co_supervisor_ids'] = $student->coSupervisors
            ->map(fn($sv) => ['lecturer_id' => $sv->id])
            ->values()
            ->toArray();

        if ($student->progress) {
            $data['progress'] = $student->progress->toArray();

            $links = $student->progress->gdrive_links ?? [];
            foreach (['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07'] as $phase) {
                $data['gdrive_' . strtolower($phase)] = collect($links)
                    ->filter(fn($link) => ($link['phase'] ?? '') === $phase)
                    ->values()
                    ->toArray();
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $progressData = $this->data['progress'] ?? [];

        $allLinks = [];
        foreach (['p01', 'p02', 'p03', 'p04', 'p05', 'p06', 'p07'] as $phase) {
            $links = $this->data['gdrive_' . $phase] ?? [];
            foreach ($links as $link) {
                $allLinks[] = [
                    'phase' => strtoupper($phase),
                    'label' => $link['label'],
                    'url'   => $link['url'],
                ];
            }
        }

        $this->record->progress()->updateOrCreate(
            ['student_id' => $this->record->id],
            [
                'eng_test_status'            => $progressData['eng_test_status'] ?? 'Pending',
                'research_method'            => $progressData['research_method'] ?? 'Pending',
                'pd_status'                  => $progressData['pd_status'] ?? 'Pending',
                'pre_viva_status'            => $progressData['pre_viva_status'] ?? 'Pending',
                'viva_status'                => $progressData['viva_status'] ?? 'Pending',
                'scholarship_status'         => $progressData['scholarship_status'] ?? 'Not Applicable',
                'tuition_fee_status'         => $progressData['tuition_fee_status'] ?? 'Pending',
                'progress_report_status'     => $progressData['progress_report_status'] ?? 'Pending',
                'last_progress_report_date'  => $progressData['last_progress_report_date'] ?? null,
                'degree_verification_status' => $progressData['degree_verification_status'] ?? 'Pending',
                'graduation_date'            => $progressData['graduation_date'] ?? null,
                'gdrive_links'               => $allLinks,
            ]
        );


            
    $coSvIds = collect($this->data['co_supervisor_ids'] ?? [])
        ->pluck('lecturer_id')
        ->filter()
        ->unique()
        ->toArray();

    $this->record->coSupervisors()->sync($coSvIds);
       $this->record->update([
                'email'                   => $this->data['email'] ?? $this->record->email,
                'gender'                  => $this->data['gender'] ?? $this->record->gender,
                'program_type'            => $this->data['program_type'] ?? $this->record->program_type,
                'payment_method'          => $this->data['payment_method'] ?? $this->record->payment_method,
                'intake_session'          => $this->data['intake_session'] ?? $this->record->intake_session,
                'intake_month' => $this->data['intake_month'] ?? $this->record->intake_month,
                'status'                  => $this->data['status'] ?? $this->record->status,
                'country'                 => $this->data['country'] ?? $this->record->country,
                'main_sv_id'              => empty($this->data['main_sv_id']) ? null : $this->data['main_sv_id'],
                'extended_semesters'      => $this->data['extended_semesters'] ?? 0,
                'extension_reason'        => $this->data['extension_reason'] ?? null,
                'extension_status'        => $this->data['extension_status'] ?? 'None',
                'extension_requested_at'  => isset($this->data['extension_status']) && $this->data['extension_status'] === 'Pending' && !$this->record->extension_requested_at
                                                ? now()
                                                : $this->record->extension_requested_at,
                'extension_approved_at'   => isset($this->data['extension_status']) && $this->data['extension_status'] === 'Approved' && !$this->record->extension_approved_at
                                                ? now()
                                                : $this->record->extension_approved_at,
            ]);

        if ($this->record->applicant) {
            $this->record->applicant->update([
                'eng_test'       => $this->data['eng_test'] ?? $this->record->applicant->eng_test,
                'prev_edu'       => $this->data['applicant_prev_edu'] ?? $this->record->applicant->prev_edu,
                'gender'         => $this->data['gender'] ?? $this->record->applicant->gender,
                'email'          => $this->data['email'] ?? $this->record->applicant->email,
                'intake_session' => $this->data['intake_session'] ?? $this->record->intake_session,
                'intake_month' => $this->data['intake_month'] ?? $this->record->intake_month,
                'country' => $this->data['country'] ?? $this->record->applicant->country,
            ]);
        }
    }
}