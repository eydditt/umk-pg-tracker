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
        $progress = $this->record->progress;
        if ($progress) {
            $data['progress'] = $progress->toArray();
        }
        return $data;
    }

    protected function afterSave(): void
    {
        $progressData = $this->data['progress'] ?? [];

        $this->record->progress()->updateOrCreate(
            ['student_id' => $this->record->id],
            [
                'eng_test_status' => $progressData['eng_test_status'] ?? 'Pending',
                'research_method' => $progressData['research_method'] ?? 'Pending',
                'pd_status'       => $progressData['pd_status'] ?? 'Pending',
                'pre_viva_status' => $progressData['pre_viva_status'] ?? 'Pending',
                'viva_status'     => $progressData['viva_status'] ?? 'Pending',
                'gdrive_links'    => $progressData['gdrive_links'] ?? [],
            ]
        );
    }
}