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

        $this->command->info('Bertenang... Sifu tengah jana 30 Pensyarah dan 350 Pelajar!');

        // ── 1. JANA 30 LECTURERS (SUPERVISORS) ──
        $titles = ['Dr.', 'Prof. Madya Dr.', 'Prof. Dr.', 'Ts. Dr.'];
        $lecIds = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $lecturer = Lecturer::create([
                'staff_no'  => 'UMK' . $faker->unique()->numerify('####'),
                'full_name' => $faker->randomElement($titles) . ' ' . $faker->name,
            ]);
            $lecIds[] = $lecturer->id;
        }

        // ── 2. SENARAI 10 TAHUN INTAKE SESSION (2017 - 2027) ──
        $programs = ['Master', 'PhD'];
        $intakes = [
            '2017/2018', '2018/2019', '2019/2020', '2020/2021', '2021/2022', 
            '2022/2023', '2023/2024', '2024/2025', '2025/2026', '2026/2027'
        ];
        $paymentMethods = ['Scholarship', 'Self-funded', 'Other', 'Not-stated'];
        
        // ── 3. JANA 350 APPLICANTS YANG DILULUSKAN (JADI STUDENT) ──
        for ($i = 1; $i <= 350; $i++) {
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
                'prev_edu'        => 'Bachelor of ' . $faker->jobTitle,
                'eng_test_taken'  => $engTestTaken,
                'eng_test'        => $engTestTaken === 'Taken' ? $faker->randomElement(['IELTS 6.0', 'MUET Band 4', 'MUET Band 5']) : null,
                'status'          => 'Approved',
            ]);

            // Pelajar ada pelbagai status
            $studentStatus = $faker->randomElement(
                array_fill(0, 60, 'Active') + 
                array_fill(60, 25, 'Completed') + 
                array_fill(85, 10, 'Deferred') + 
                array_fill(95, 5, 'Terminated')
            );

            // 10% pelajar tiada SV (Unsupervised)
            $isUnsupervised = $faker->boolean(10);

            $student = Student::create([
                'applicant_id'           => $applicant->id,
                'matric_no'              => ($program === 'PhD' ? 'A' : 'M') . $faker->unique()->numerify('2#P####'),
                'email'                  => $applicant->email,
                'program_type'           => $program,
                'intake_session'         => $applicant->intake_session,
                'gender'                 => $gender,
                'nationality_type'       => $isLocal ? 'Local' : 'International',
                'payment_method'         => $faker->randomElement($paymentMethods),
                'main_sv_id'             => $isUnsupervised ? null : $mainSv,
                'co_sv_id'               => $isUnsupervised ? null : $coSv,
                'status'                 => $studentStatus,
                'application_docs_links' => [],
            ]);

            $isCompleted = ($studentStatus === 'Completed');

            StudentProgress::create([
                'student_id'                 => $student->id,
                'eng_test_status'            => $engTestTaken === 'Taken' ? 'Passed' : 'Pending',
                'research_method'            => $isCompleted ? 'Passed' : $faker->randomElement(['Pending', 'Passed']),
                'pd_status'                  => $isCompleted ? 'Passed' : $faker->randomElement(['Pending', 'Passed', 'Minor Correction']),
                'pre_viva_status'            => $isCompleted ? 'Passed' : $faker->randomElement(['Pending', 'Passed']),
                'viva_status'                => $isCompleted ? 'Passed' : $faker->randomElement(['Pending', 'Passed']),
                'scholarship_status'         => $faker->randomElement(['Pending', 'Approved', 'Not Applicable']),
                'tuition_fee_status'         => $faker->randomElement(['Pending', 'Paid']),
                'progress_report_status'     => $faker->randomElement(['Pending', 'Submitted', 'Approved']),
                'last_progress_report_date'  => $faker->optional()->date(),
                'degree_verification_status' => $isCompleted ? 'Awarded' : 'Pending',
                'graduation_date'            => $isCompleted ? $faker->date() : null,
            ]);
        }

        // ── 4. JANA 50 PENDING / REJECTED APPLICANTS ──
        for ($i = 1; $i <= 50; $i++) {
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