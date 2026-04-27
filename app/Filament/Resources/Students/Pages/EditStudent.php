<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
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
            $data['application_docs_links'] = $student->application_docs_links ?? [];
        }

        if ($student->progress) {
            $data['progress'] = $student->progress->toArray();

            $links = $student->progress->gdrive_links ?? [];
            foreach (['P01', 'P02', 'P03', 'P04', 'P05'] as $phase) {
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

        // Gabungkan semua gdrive links dari setiap phase
        $allLinks = [];
        foreach (['p01', 'p02', 'p03', 'p04', 'p05'] as $phase) {
            $links = $this->data['gdrive_' . $phase] ?? [];
            foreach ($links as $link) {
                $allLinks[] = [
                    'phase' => strtoupper($phase),
                    'label' => $link['label'],
                    'url'   => $link['url'],
                ];
            }
        }

        // Save progress
        $this->record->progress()->updateOrCreate(
            ['student_id' => $this->record->id],
            [
                'eng_test_status' => $progressData['eng_test_status'] ?? 'Pending',
                'research_method' => $progressData['research_method'] ?? 'Pending',
                'pd_status'       => $progressData['pd_status'] ?? 'Pending',
                'pre_viva_status' => $progressData['pre_viva_status'] ?? 'Pending',
                'viva_status'     => $progressData['viva_status'] ?? 'Pending',
                'gdrive_links'    => $allLinks,
            ]
        );

        // Save student fields
        $this->record->update([
            'email'          => $this->data['email'] ?? $this->record->email,
            'gender'         => $this->data['gender'] ?? $this->record->gender,
            'program_type'   => $this->data['program_type'] ?? $this->record->program_type,
            'payment_method' => $this->data['payment_method'] ?? $this->record->payment_method,
            'status'         => $this->data['status'] ?? $this->record->status,
            'main_sv_id'     => $this->data['main_sv_id'] ?? null,
            'co_sv_id'       => $this->data['co_sv_id'] ?? null,
        ]);

        // Sync back to applicant
        if ($this->record->applicant) {

            $this->record->applicant->update([
                'eng_test' => $this->data['eng_test'] ?? $this->record->applicant->eng_test,
                'prev_edu' => $this->data['applicant_prev_edu'] ?? $this->record->applicant->prev_edu,
                'gender'   => $this->data['gender'] ?? $this->record->applicant->gender,
                'email'    => $this->data['email'] ?? $this->record->applicant->email,
            ]);
        }
    }
}