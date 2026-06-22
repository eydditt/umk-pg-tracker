<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Students'),

            'no_sv' => Tab::make('No Supervisor')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->whereNull('main_sv_id')
                    ->where('status', 'Active')),

            'pending_english' => Tab::make('Pending English')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->whereHas('progress', fn($q) => $q
                        ->where('eng_test_status', 'Pending'))
                    ->where('status', 'Active')),

            'pending_proposal' => Tab::make('Pending Proposal')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->whereHas('progress', fn($q) => $q
                        ->where('pd_status', 'Pending'))
                    ->where('status', 'Active')),

            'pending_viva' => Tab::make('Pending Viva')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->whereHas('progress', fn($q) => $q
                        ->where('viva_status', 'Pending'))
                    ->where('status', 'Active')),
        ];
    }
}