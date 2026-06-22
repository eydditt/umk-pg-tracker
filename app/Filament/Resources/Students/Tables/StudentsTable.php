<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('matric_no')
                    ->label('Matric No')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Matric No copied!'),

                TextColumn::make('applicant.full_name')
                    ->label('Student Name')
                    ->searchable()
                    ->wrap()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->applicant?->full_name),

                TextColumn::make('program_type')
                    ->label('Program')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'PhD'    => 'phd',
                        'Master' => 'master',
                        default  => 'gray',
                    }),

               TextColumn::make('intake_session')
                    ->label('Intake')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('current_semester')
                    ->label('Sem')
                    ->tooltip(fn($record) => match(true) {
                        in_array($record->status, ['Completed', 'Terminated']) => 'Student is ' . $record->status,
                        !$record->intake_session => 'No intake session set',
                        $record->is_exceeded_semester => '⚠️ Exceeded! Max: ' . $record->effective_max_semester . ' sem',
                        default => 'Semester ' . $record->current_semester . ' of ' . $record->effective_max_semester,
                    })
                    ->badge()
                    ->color(fn($record) => match(true) {
                        in_array($record->status, ['Completed', 'Terminated']) => 'gray',
                        !$record->intake_session => 'gray',
                        $record->is_exceeded_semester => 'danger',
                        $record->current_semester >= $record->effective_max_semester => 'warning',
                        default => 'success',
                    })
                    ->getStateUsing(function($record) {
                        if (in_array($record->status, ['Completed', 'Terminated'])) return '—';
                        if (!$record->current_semester) return '—';
                        return 'Sem ' . $record->current_semester . ($record->is_exceeded_semester ? ' ⚠️' : '');
                    }),

                TextColumn::make('mainSupervisor.full_name')
                    ->label('Main SV')
                    ->placeholder('— Unassigned —')
                    ->color(fn($record) => $record->main_sv_id ? 'success' : 'danger')
                    ->limit(22)
                    ->tooltip(fn($record) => $record->mainSupervisor?->full_name ?? 'No supervisor assigned')
                    ->wrap(),

                TextColumn::make('coSupervisors.full_name')
                    ->label('Co-SV')
                    ->placeholder('—')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('progress.eng_test_status')
                    ->label('English')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Passed'  => 'success',
                        'Pending' => 'warning',
                        default   => 'gray',
                    }),

                // ── MILESTONE ICONS ──────────────────────────
                IconColumn::make('progress.research_method')
                    ->label('RM')
                    ->tooltip('Research Method')
                    ->icon(fn($state): string => match($state) {
                        'Passed'  => 'heroicon-o-check-circle',
                        'Pending' => 'heroicon-o-clock',
                        default   => 'heroicon-o-minus-circle',
                    })
                    ->color(fn($state): string => match($state) {
                        'Passed'  => 'success',
                        'Pending' => 'warning',
                        default   => 'gray',
                    }),

                IconColumn::make('progress.pd_status')
                    ->label('PD')
                    ->tooltip('Proposal Defense')
                    ->icon(fn($state): string => match($state) {
                        'Passed'           => 'heroicon-o-check-circle',
                        'Minor Correction' => 'heroicon-o-exclamation-circle',
                        'Major Correction' => 'heroicon-o-exclamation-triangle',
                        'Pending'          => 'heroicon-o-clock',
                        default            => 'heroicon-o-minus-circle',
                    })
                    ->color(fn($state): string => match($state) {
                        'Passed'           => 'success',
                        'Minor Correction' => 'info',
                        'Major Correction' => 'danger',
                        'Pending'          => 'warning',
                        default            => 'gray',
                    }),

                IconColumn::make('progress.pre_viva_status')
                    ->label('PV')
                    ->tooltip('Pre-Viva')
                    ->icon(fn($state): string => match($state) {
                        'Passed'  => 'heroicon-o-check-circle',
                        'Failed'  => 'heroicon-o-x-circle',
                        'Pending' => 'heroicon-o-clock',
                        default   => 'heroicon-o-minus-circle',
                    })
                    ->color(fn($state): string => match($state) {
                        'Passed'  => 'success',
                        'Failed'  => 'danger',
                        'Pending' => 'warning',
                        default   => 'gray',
                    }),

                IconColumn::make('progress.viva_status')
                    ->label('Viva')
                    ->tooltip('Viva Examination')
                    ->icon(fn($state): string => match($state) {
                        'Passed'  => 'heroicon-o-check-circle',
                        'Failed'  => 'heroicon-o-x-circle',
                        'Pending' => 'heroicon-o-clock',
                        default   => 'heroicon-o-minus-circle',
                    })
                    ->color(fn($state): string => match($state) {
                        'Passed'  => 'success',
                        'Failed'  => 'danger',
                        'Pending' => 'warning',
                        default   => 'gray',
                    }),

                TextColumn::make('progress.progress_report_status')
                    ->label('Report')
                    ->tooltip('Progress Report Status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Approved'  => 'success',
                        'Submitted' => 'info',
                        'Rejected'  => 'danger',
                        'Pending'   => 'warning',
                        default     => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'Active'     => 'success',
                        'Completed'  => 'info',
                        'Terminated' => 'danger',
                        'Deferred'   => 'warning',
                        default      => 'gray',
                    }),
            ])

            ->filters([
                SelectFilter::make('program_type')
                    ->label('Program')
                    ->options(['Master' => 'Master', 'PhD' => 'PhD']),
                SelectFilter::make('status')
                    ->label('Student Status')
                    ->options([
                        'Active'     => 'Active',
                        'Completed'  => 'Completed',
                        'Terminated' => 'Terminated',
                        'Deferred'   => 'Deferred',
                    ]),
                SelectFilter::make('gender')
                    ->label('Gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female']),
                SelectFilter::make('main_sv_id')
                    ->label('Supervision')
                    ->options([
                        'unassigned' => 'Unassigned Only',
                    ])
                    ->query(fn($query, $state) =>
                        $state['value'] === 'unassigned'
                            ? $query->whereNull('main_sv_id')
                            : $query
                    ),
            ])

            ->headerActions([
                Action::make('status_guide')
                    ->label('Legend')
                    ->icon('heroicon-o-information-circle')
                    ->color('gray')
                    ->modalHeading('Column & Status Legend')
                    ->modalDescription(new HtmlString('
                        <div style="font-size:13px; line-height:1.8;">

                            <p style="font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; color:#6B7280; margin-bottom:8px;">
                                Milestone Columns
                            </p>
                            <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:6px 10px; font-weight:700; border:1px solid rgba(128,128,128,0.2); width:60px; background:rgba(128,128,128,0.05);">RM</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Research Method</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; font-weight:700; border:1px solid rgba(128,128,128,0.2);">PD</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Proposal Defense</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; font-weight:700; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">PV</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Pre-Viva</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; font-weight:700; border:1px solid rgba(128,128,128,0.2);">Viva</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Viva Examination</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; font-weight:700; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Report</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Progress Report Status</td>
                                </tr>
                            </table>

                            <p style="font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; color:#6B7280; margin-bottom:8px;">
                                Icon & Colour Meanings
                            </p>
                            <table style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); width:30px; background:rgba(128,128,128,0.05);">✅</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#10B981; font-weight:700; background:rgba(128,128,128,0.05);">Green</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Passed / Approved / Active</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">🕒</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#F59E0B; font-weight:700;">Amber</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Pending / Deferred</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">❌</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#EF4444; font-weight:700; background:rgba(128,128,128,0.05);">Red</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Failed / Terminated / Rejected</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">⚠️</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#EF4444; font-weight:700;">Red Triangle</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Major Correction Required</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">ℹ️</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#3B82F6; font-weight:700; background:rgba(128,128,128,0.05);">Blue</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); background:rgba(128,128,128,0.05);">Minor Correction / Submitted</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">➖</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2); color:#9CA3AF; font-weight:700;">Gray</td>
                                    <td style="padding:6px 10px; border:1px solid rgba(128,128,128,0.2);">Not applicable / No data</td>
                                </tr>
                            </table>
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Got it!'),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Permanently Delete Selected Students')
                        ->modalDescription(new HtmlString(
                            'Are you sure you want to delete the selected students? This action is irreversible.<br><br>
                            <strong>⚠️ Note:</strong> Linked applicant records will remain for reference. Only student and progress data will be deleted.'
                        ))
                        ->modalSubmitActionLabel('Yes, Delete Selected')
                        ->action(function($records) {
                            $records->each(function($record) {
                                $record->progress()->delete();
                                $record->forceDelete();
                            });
                        }),
                ]),
            ])

            ->defaultSort(fn($query) => $query
            ->orderByRaw("FIELD(status, 'Active', 'Deferred', 'Completed', 'Terminated')")
            ->orderBy('matric_no', 'asc')
)
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}