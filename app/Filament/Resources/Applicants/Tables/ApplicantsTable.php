<?php

namespace App\Filament\Resources\Applicants\Tables;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\StudentProgress;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    
                    ->wrap()
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->full_name),
                    
                TextColumn::make('identity_no')
                    ->label('Identity No')
                    ->searchable(),
                    
                TextColumn::make('program_applied')
                    ->label('Program')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'PhD'    => 'phd',
                        'Master' => 'master',
                        default  => 'gray',
                    }),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'Pending'  => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default    => 'gray',
                    }),
                    
                TextColumn::make('intake_session')
                    ->label('Intake')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Pending'  => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ]),

                SelectFilter::make('program_applied')
                    ->options([
                        'Master' => 'Master',
                        'PhD'    => 'PhD',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn(Applicant $record) => $record->status === 'Pending'), 

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Applicant $record) => $record->status === 'Pending')
                    ->form([
                        TextInput::make('matric_no')
                            ->label('New Matric Number')
                            ->required()
                            ->placeholder('e.g. A23PM0001')
                            ->rules(['unique:students,matric_no']),
                    ])
                   ->action(function(Applicant $record, array $data) {
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
                        ]);

                        Notification::make()
                            ->title('Applicant approved! Student record created.')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Applicant')
                    ->modalDescription('This applicant will be marked as Rejected and kept in the system for record purposes.')
                    ->visible(fn(Applicant $record) => $record->status === 'Pending')
                    ->action(function(Applicant $record) {
                        $record->update(['status' => 'Rejected']);
                        Notification::make()
                            ->title('Applicant rejected.')
                            ->warning()
                            ->send();
                    }),

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
                    ->visible(fn(Applicant $record) => $record->status !== 'Approved')
                    ->action(function(Applicant $record) {
                        $record->forceDelete();

                        Notification::make()
                            ->title('Applicant permanently deleted.')
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort(fn($query) => $query->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected')"))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Permanently Delete Selected Applicants')
                        ->modalDescription(new HtmlString(
                            'Are you sure you want to delete the selected applicants? This action is irreversible, and all records will be permanently removed from the system.<br><br>
                            <strong>⚠️ Note:</strong> If any applicant has already become a student, their student and progress records will ALSO be deleted to prevent orphaned data.'
                        ))
                        ->modalSubmitActionLabel('Yes, Delete All')
                        ->action(function($records) {
                          
                            $records->each(function ($record) {
                        
                                $student = Student::where('applicant_id', $record->id)->first();
                                
                                if ($student) {
                                    $student->progress()->delete(); 
                                    $student->forceDelete();        
                                }
                                
                                $record->forceDelete(); 
                            });
                        }),
                ]),
            ]);
    }
}