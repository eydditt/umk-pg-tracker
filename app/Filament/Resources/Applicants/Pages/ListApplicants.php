<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use App\Filament\Resources\Applicants\Widgets\ApplicantStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicants extends ListRecords
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ApplicantStats::class,
        ];
    }
}