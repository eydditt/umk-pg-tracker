<?php

namespace App\Filament\Resources\Lecturers\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lecturer Information')->schema([
                TextInput::make('staff_no')
                    ->label('Staff No')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),
            ])->columns(2),

            Section::make('Supervised Students')
                ->description('List of students currently supervised by this lecturer. (View Only)')
                ->schema([
                    Placeholder::make('students_list')
                        ->label('')
                        ->content(function ($record) {
                            if (! $record) {
                                return new HtmlString('<span style="color: #6b7280; font-style: italic;">Please save the lecturer first to view students.</span>');
                            }

                            // Logik Sorting: Active kat atas, yang lain kat bawah
                            $students = $record->mainStudents()->with('applicant')->get()
                                ->sortBy(function ($student) {
                                    return match ($student->status) {
                                        'Active'     => 1,
                                        'Deferred'   => 2,
                                        'Completed'  => 3,
                                        'Terminated' => 4,
                                        default      => 5,
                                    };
                                });

                            if ($students->isEmpty()) {
                                return new HtmlString('<span style="color: #6b7280; font-style: italic;">No students assigned to this lecturer yet.</span>');
                            }
                            
                            // 👇 SIFU KIRA JUMLAH KESELURUHAN PELAJAR DI SINI
                            $totalStudents = $students->count();

                            // 👇 SIFU TAMBAH LENCANA TOTAL & MAGIK SCROLL (max-height & sticky)
                            $html = '
                            <div style="margin-bottom: 12px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #6b7280;">Total All-Time Students: </span> 
                                <span style="background-color: #008080; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 12px;">' . $totalStudents . '</span>
                            </div>
                            
                            <div style="border: 1px solid rgba(128, 128, 128, 0.2); border-radius: 8px; overflow-y: auto; max-height: 350px;">
                                <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px;">
                                    <thead class="bg-gray-50 dark:bg-gray-800" style="position: sticky; top: 0; z-index: 10; border-bottom: 1px solid rgba(128, 128, 128, 0.2); box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                                        <tr>
                                            <th style="padding: 12px 16px; font-weight: bold; width: 15%;">Matric No</th>
                                            <th style="padding: 12px 16px; font-weight: bold; width: 45%;">Student Name</th>
                                            <th style="padding: 12px 16px; font-weight: bold; width: 20%;">Program</th>
                                            <th style="padding: 12px 16px; font-weight: bold; width: 20%;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: transparent;">
                            ';

                            foreach ($students as $student) {
                                $name = $student->applicant ? e($student->applicant->full_name) : 'Unknown';
                                $matric = e($student->matric_no);
                                
                                $programBg = $student->program_type === 'PhD' 
                                    ? 'background-color: rgba(124, 58, 237, 0.1); color: #7c3aed;' 
                                    : 'background-color: rgba(8, 145, 178, 0.1); color: #0891b2;';
                                $program = "<span style=\"padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; {$programBg}\">" . e($student->program_type) . "</span>";
                                
                                $statusStyle = match($student->status) {
                                    'Active'     => 'color: #059669; font-weight: 600;',
                                    'Completed'  => 'color: #2563eb; font-weight: 600;',
                                    'Terminated' => 'color: #dc2626; font-weight: 600;',
                                    'Deferred'   => 'color: #d97706; font-weight: 600;',
                                    default      => 'color: #4b5563; font-weight: 600;',
                                };
                                $status = "<span style=\"{$statusStyle}\">" . e($student->status) . "</span>";

                                $html .= "
                                    <tr style=\"border-bottom: 1px solid rgba(128, 128, 128, 0.1);\">
                                        <td style=\"padding: 12px 16px; font-weight: 600;\">{$matric}</td>
                                        <td style=\"padding: 12px 16px;\">{$name}</td>
                                        <td style=\"padding: 12px 16px;\">{$program}</td>
                                        <td style=\"padding: 12px 16px;\">{$status}</td>
                                    </tr>
                                ";
                            }

                            $html .= '
                                    </tbody>
                                </table>
                            </div>';

                            return new HtmlString($html);
                        })
                        ->columnSpanFull(),
                ])
                ->visible(fn ($record) => $record !== null), 
        ]);
    }
}