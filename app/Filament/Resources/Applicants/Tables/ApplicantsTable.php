<?php

namespace App\Filament\Resources\Applicants\Tables;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\StudentProgress;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('identity_no')->searchable(),
                TextColumn::make('program_applied')->badge()
                    ->color(fn(string $state) => match($state) {
                        'PhD'    => 'danger',
                        'Master' => 'info',
                        default  => 'gray',
                    }),
                TextColumn::make('status')->badge()
                    ->color(fn(string $state) => match($state) {
                        'Pending'  => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default    => 'gray',
                    }),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected']),
            ])
            ->actions([
                EditAction::make(),
                Action::make('approve')
                    ->label('Luluskan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Applicant $record) => $record->status === 'Pending')
                    ->form([
                        TextInput::make('matric_no')
                            ->label('Nombor Matrik Baharu')
                            ->required()
                            ->unique('students', 'matric_no')
                            ->placeholder('Contoh: A23PM0001'),
                    ])
                    ->action(function(Applicant $record, array $data) {
                        $record->update(['status' => 'Approved']);

                        $student = Student::create([
                            'applicant_id'   => $record->id,
                            'matric_no'      => $data['matric_no'],
                            'program_type'   => $record->program_applied,
                            'gender'         => 'Male',
                            'payment_method' => 'Self-funded',
                            'status'         => 'Active',
                        ]);

                        StudentProgress::create(['student_id' => $student->id]);

                        Notification::make()
                            ->title('Pemohon diluluskan! Rekod pelajar dicipta.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(Applicant $record) => $record->status === 'Pending')
                    ->action(function(Applicant $record) {
                        $record->update(['status' => 'Rejected']);
                        $record->delete();
                    }),
            ]);
    }
}