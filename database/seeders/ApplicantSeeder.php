<?php

namespace Database\Seeders;

use App\Models\Applicant;
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Ahmad Faris Bin Ismail', 'Nurul Ain Binti Hassan', 'Muhammad Haziq Bin Ramli',
            'Siti Norfazilah Binti Yusof', 'Wan Amirul Bin Wan Aziz', 'Farah Nadia Binti Zakaria',
            'Mohamad Aizat Bin Jamaludin', 'Nur Syahirah Binti Abdullah', 'Hafizuddin Bin Mohd Noor',
            'Aishah Binti Sulaiman', 'Razif Bin Kamaruddin', 'Zulaikha Binti Othman',
            'Izzul Haqimi Bin Roslan', 'Nabilah Binti Md Zain', 'Syafiq Bin Mustafa',
            'Hanis Binti Hamid', 'Azri Bin Zainal', 'Fatin Husna Binti Nordin',
            'Luqmanul Hakim Bin Salleh', 'Marsya Binti Ibrahim',
        ];

        foreach ($names as $i => $name) {
            $gender       = $i % 2 === 0 ? 'Male' : 'Female';
            $program      = $i % 2 === 0 ? 'Master' : 'PhD';
            $identityType = $i < 15 ? 'IC' : 'Passport';
            $identityNo   = $identityType === 'IC'
                ? '9' . str_pad($i + 1, 11, '0', STR_PAD_LEFT)
                : 'P' . str_pad($i + 1, 8, '0', STR_PAD_LEFT);

            Applicant::create([
                'full_name'       => $name,
                'email'           => 'student' . ($i + 1) . '@gmail.com',
                'gender'          => $gender,
                'identity_type'   => $identityType,
                'identity_no'     => $identityNo,
                'program_applied' => $program,
                'prev_edu'        => 'Bachelor of ' . ($i % 2 === 0 ? 'Computer Science' : 'Information Technology'),
                'eng_test'        => $i % 3 === 0 ? null : (string)(5 + ($i % 4) * 0.5),
                'eng_test_taken'  => $i % 3 === 0 ? 'Not Taken' : 'Taken',
                'status'          => 'Pending',
            ]);
        }
    }
}