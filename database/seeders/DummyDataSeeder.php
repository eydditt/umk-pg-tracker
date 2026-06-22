<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ms_MY');

        // ── 0. ADMIN ACCOUNT ──
        User::updateOrCreate(
            ['email' => 'pascasiswazah.fsdk@umk.edu.my'],
            [
                'name'     => 'FSDK Admin',
                'password' => Hash::make('123456789'),
            ]
        );
        $this->command->info('✅ Admin account created');

        // ── 1. LECTURERS ──
        $titles = ['Dr.', 'Prof. Madya Dr.', 'Prof. Dr.', 'Ts. Dr.'];
        $lecIds = [];

        for ($i = 1; $i <= 30; $i++) {
            $lecturer = Lecturer::create([
                'staff_no'  => 'UMK' . $faker->unique()->numerify('####'),
                'full_name' => $faker->randomElement($titles) . ' ' . $faker->name,
            ]);
            $lecIds[] = $lecturer->id;
        }
        $this->command->info('✅ 30 Lecturers created');

        // ── 2. INTAKE SESSIONS ──
        $intakeSessions = [];
        for ($year = 2018; $year <= 2026; $year++) {
            $intakeSessions[] = ['session' => "{$year}/" . ($year + 1), 'month' => 'September'];
            $intakeSessions[] = ['session' => "{$year}/" . ($year + 1), 'month' => 'February'];
        }

        // ── 3. COUNTRY LIST ──
        $localCountries = ['Malaysia'];
        $intlCountries  = [
            'Indonesia', 'China', 'India', 'Bangladesh', 'Pakistan',
            'Nigeria', 'Yemen', 'Jordan', 'Iran', 'Somalia',
            'Egypt', 'Sudan', 'Ghana', 'Kenya', 'Saudi Arabia',
            'Vietnam', 'Thailand', 'Philippines', 'South Korea', 'Japan',
        ];

        $programs       = ['Master', 'PhD'];
        $paymentMethods = ['Scholarship', 'Self-funded', 'Other', 'Not-stated'];
        $studentStatuses = array_merge(
            array_fill(0, 60, 'Active'),
            array_fill(0, 25, 'Completed'),
            array_fill(0, 10, 'Deferred'),
            array_fill(0, 5, 'Terminated')
        );

        // ── 4. APPROVED APPLICANTS → STUDENTS ──
        for ($i = 1; $i <= 250; $i++) {
            $gender       = $faker->randomElement(['Male', 'Female']);
            $isLocal      = $faker->boolean(75);
            $program      = $faker->randomElement($programs);
            $engTestTaken = $faker->randomElement(['Taken', 'Not Taken', 'Not Required']);
            $intake       = $faker->randomElement($intakeSessions);
            $country      = $isLocal
                ? 'Malaysia'
                : $faker->randomElement($intlCountries);

            $applicant = Applicant::create([
                'full_name'       => $faker->name($gender === 'Male' ? 'male' : 'female'),
                'email'           => $faker->unique()->safeEmail(),
                'gender'          => $gender,
                'identity_type'   => $isLocal ? 'IC' : 'Passport',
                'identity_no'     => $isLocal
                    ? $faker->numerify('############')
                    : strtoupper($faker->bothify('?########')),
                'country'         => $country,
                'program_applied' => $program,
                'intake_session'  => $intake['session'],
                'intake_month'    => $intake['month'],
                'prev_edu'        => 'Bachelor of ' . $faker->jobTitle,
                'eng_test_taken'  => $engTestTaken,
                'eng_test'        => $engTestTaken === 'Taken'
                    ? $faker->randomElement(['IELTS 6.0', 'IELTS 6.5', 'MUET Band 4', 'MUET Band 5'])
                    : null,
                'status'          => 'Approved',
            ]);

            $studentStatus  = $faker->randomElement($studentStatuses);
            $isUnsupervised = $faker->boolean(10);
            $mainSv         = $isUnsupervised ? null : $faker->randomElement($lecIds);

            $student = Student::create([
                'applicant_id'           => $applicant->id,
                'matric_no'              => ($program === 'PhD' ? 'A' : 'M') . $faker->unique()->numerify('2#P####'),
                'email'                  => $applicant->email,
                'program_type'           => $program,
                'intake_session'         => $intake['session'],
                'intake_month'           => $intake['month'],
                'gender'                 => $gender,
                'nationality_type'       => $isLocal ? 'Local' : 'International',
                'country'                => $country,
                'payment_method'         => $faker->randomElement($paymentMethods),
                'main_sv_id'             => $mainSv,
                'status'                 => $studentStatus,
                'application_docs_links' => [],
                'extended_semesters'     => 0,
                'extension_status'       => 'None',
            ]);

            // Assign 0-2 co-supervisors via pivot
            if ($mainSv && $faker->boolean(40)) {
                $availableLecs = array_diff($lecIds, [$mainSv]);
                $coCount       = $faker->numberBetween(1, 2);
                $coIds         = $faker->randomElements($availableLecs, min($coCount, count($availableLecs)));
                $student->coSupervisors()->sync($coIds);
            }

            $isCompleted = ($studentStatus === 'Completed');

            // Eng test status based on eng_test_taken
            $engStatus = match($engTestTaken) {
                'Taken'        => 'Passed',
                'Not Required' => 'Not Required',
                default        => 'Pending',
            };

            StudentProgress::create([
                'student_id'                 => $student->id,
                'eng_test_status'            => $engStatus,
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
        $this->command->info('✅ 250 Students created');

        // ── 5. PENDING / REJECTED APPLICANTS ──
        for ($i = 1; $i <= 30; $i++) {
            $gender  = $faker->randomElement(['Male', 'Female']);
            $isLocal = $faker->boolean(80);
            $intake  = $faker->randomElement($intakeSessions);

            Applicant::create([
                'full_name'       => $faker->name($gender === 'Male' ? 'male' : 'female'),
                'email'           => $faker->unique()->safeEmail(),
                'gender'          => $gender,
                'identity_type'   => $isLocal ? 'IC' : 'Passport',
                'identity_no'     => $isLocal
                    ? $faker->numerify('############')
                    : strtoupper($faker->bothify('?########')),
                'country'         => $isLocal ? 'Malaysia' : $faker->randomElement($intlCountries),
                'program_applied' => $faker->randomElement($programs),
                'intake_session'  => $intake['session'],
                'intake_month'    => $intake['month'],
                'prev_edu'        => 'Bachelor of ' . $faker->jobTitle,
                'eng_test_taken'  => $faker->randomElement(['Taken', 'Not Taken']),
                'eng_test'        => null,
                'status'          => $faker->randomElement(['Pending', 'Pending', 'Rejected']),
            ]);
        }
        $this->command->info('✅ 30 Pending/Rejected Applicants created');

        $this->command->info('');
        $this->command->info('=== SEEDER COMPLETE ===');
        $this->command->info('Lecturers  : ' . Lecturer::count());
        $this->command->info('Applicants : ' . Applicant::count());
        $this->command->info('Students   : ' . Student::count());
        $this->command->info('Progress   : ' . StudentProgress::count());
        $this->command->info('Co-SVs     : ' . DB::table('student_co_supervisors')->count());
    }
}