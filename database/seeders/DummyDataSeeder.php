<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil Faker dengan setting nama orang Malaysia!
        $faker = Faker::create('ms_MY');

        // ── 1. LECTURERS (Sifu kekalkan nama-nama sedia ada) ──
        $lecturers = [
            ['staff_no' => 'STF001', 'full_name' => 'Dr. Ahmad Fauzi bin Ismail'],
            ['staff_no' => 'STF002', 'full_name' => 'Dr. Siti Nur Aisyah binti Zakaria'],
            ['staff_no' => 'STF003', 'full_name' => 'Prof. Madya Dr. Mohd Hafiz bin Abdullah'],
            ['staff_no' => 'STF004', 'full_name' => 'Dr. Nurul Huda binti Rahman'],
            ['staff_no' => 'STF005', 'full_name' => 'Dr. Khairul Anwar bin Mohamad'],
        ];

        foreach ($lecturers as $l) {
            Lecturer::firstOrCreate(['staff_no' => $l['staff_no']], $l);
        }

        // Ambil senarai ID pensyarah untuk diagihkan kepada pelajar nanti
        $lecIds = Lecturer::pluck('id')->toArray();

        // ── 2. JANA 50 APPLICANTS YANG DILULUSKAN (JADI STUDENT) ──
        $programs = ['Master', 'PhD'];
        $intakes = ['2023/2024', '2024/2025', '2025/2026'];
        $paymentMethods = ['Scholarship', 'Self-funded', 'Other', 'Not-stated'];
        
        for ($i = 1; $i <= 50; $i++) {
            $gender = $faker->randomElement(['Male', 'Female']);
            $isLocal = $faker->boolean(80); // 80% pelajar tempatan
            $program = $faker->randomElement($programs);
            $engTestTaken = $faker->randomElement(['Taken', 'Not Taken']);
            
            // Randomize Supervisor
            $mainSv = $faker->randomElement($lecIds);
            $coSv = $faker->boolean(40) ? $faker->randomElement(array_diff($lecIds, [$mainSv])) : null;

            $applicant = Applicant::create([
                'full_name'       => $faker->name($gender === 'Male' ? 'male' : 'female'),
                'email'           => $faker->unique()->safeEmail(),
                'gender'          => $gender,
                'identity_type'   => $isLocal ? 'IC' : 'Passport',
                'identity_no'     => $isLocal ? $faker->numerify('##########') : $faker->bothify('?########'),
                'program_applied' => $program,
                'intake_session'  => $faker->randomElement($intakes),
                'prev_edu'        => 'Bachelor of ' . $faker->jobTitle . ', ' . $faker->company,
                'eng_test_taken'  => $engTestTaken,
                'eng_test'        => $engTestTaken === 'Taken' ? $faker->randomElement(['IELTS 6.0', 'MUET Band 4', 'MUET Band 5']) : null,
                'status'          => 'Approved',
            ]);

            $studentStatus = $faker->randomElement(['Active', 'Active', 'Active', 'Completed', 'Deferred']);

            $student = Student::create([
                'applicant_id'           => $applicant->id,
                'matric_no'              => ($program === 'PhD' ? 'A' : 'M') . $faker->unique()->numerify('2#P####'),
                'email'                  => $applicant->email,
                'program_type'           => $program,
                'intake_session'         => $applicant->intake_session,
                'gender'                 => $gender,
                'nationality_type'       => $isLocal ? 'Local' : 'International',
                'payment_method'         => $faker->randomElement($paymentMethods),
                'main_sv_id'             => $mainSv,
                'co_sv_id'               => $coSv,
                'status'                 => $studentStatus,
                'application_docs_links' => [],
            ]);

            StudentProgress::create([
                'student_id'                 => $student->id,
                'eng_test_status'            => $engTestTaken === 'Taken' ? 'Passed' : 'Pending',
                'research_method'            => $faker->randomElement(['Pending', 'Passed']),
                'pd_status'                  => $faker->randomElement(['Pending', 'Passed', 'Minor Correction']),
                'pre_viva_status'            => $faker->randomElement(['Pending', 'Passed']),
                'viva_status'                => $faker->randomElement(['Pending', 'Passed']),
                'scholarship_status'         => $faker->randomElement(['Pending', 'Approved', 'Not Applicable']),
                'tuition_fee_status'         => $faker->randomElement(['Pending', 'Paid']),
                'progress_report_status'     => $faker->randomElement(['Pending', 'Submitted', 'Approved']),
                'last_progress_report_date'  => $faker->optional()->date(),
                'degree_verification_status' => $studentStatus === 'Completed' ? 'Awarded' : 'Pending',
                'graduation_date'            => $studentStatus === 'Completed' ? $faker->date() : null,
            ]);
        }

        // ── 3. JANA 20 PENDING / REJECTED APPLICANTS ──
        for ($i = 1; $i <= 20; $i++) {
            $gender = $faker->randomElement(['Male', 'Female']);
            $isLocal = $faker->boolean(90);
            
            Applicant::create([
                'full_name'       => $faker->name($gender === 'Male' ? 'male' : 'female'),
                'email'           => $faker->unique()->safeEmail(),
                'gender'          => $gender,
                'identity_type'   => $isLocal ? 'IC' : 'Passport',
                'identity_no'     => $isLocal ? $faker->numerify('##########') : $faker->bothify('?########'),
                'program_applied' => $faker->randomElement($programs),
                'intake_session'  => $faker->randomElement($intakes),
                'prev_edu'        => 'Bachelor of ' . $faker->jobTitle,
                'eng_test_taken'  => $faker->randomElement(['Taken', 'Not Taken']),
                'eng_test'        => null,
                'status'          => $faker->randomElement(['Pending', 'Pending', 'Rejected']),
            ]);
        }

        $this->command->info('✅ KILANG DATA FAKER BERJAYA DIJALANKAN!');
        $this->command->info('   Jumlah Pensyarah: ' . Lecturer::count());
        $this->command->info('   Jumlah Pemohon: ' . Applicant::count());
        $this->command->info('   Jumlah Pelajar: ' . Student::count());
    }
}