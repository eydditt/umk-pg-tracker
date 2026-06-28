<?php

namespace App\Console\Commands;

use App\Models\Applicant;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportRealData extends Command
{
    protected $signature   = 'import:real-data';
    protected $description = 'Import real FSDK student data and delete dummy data';

    // ── STATUS MAPPING ──────────────────────────────────────────────────────
    private array $statusMap = [
        'AKTIF'                          => 'Active',
        'LULUS BERGRADUAT'               => 'Completed',
        'GRADUAT'                        => 'Completed',
        'DIBERHENTIKAN'                  => 'Terminated',
        'TANGGUH PENGAJIAN'              => 'Deferred',
        'TANGGUH PENDAFTARAN'            => 'Deferred',
    ];

    // Statuses to skip
    private array $skipStatuses = [
        'TIDAK MENDAFTAR - OFFER',
        'TIDAK MENDAFTAR TAHUN PERTAMA',
        'TARIK DIRI',
    ];

    public function handle(): void
    {
        $this->info('Starting import...');

        // ── STEP 1: DELETE DUMMY DATA ──────────────────────────────────────
        $this->warn('Deleting dummy data...');
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        StudentProgress::truncate();
        \DB::table('student_co_supervisors')->truncate();
        Student::query()->forceDelete();
        Applicant::query()->forceDelete();
        Lecturer::truncate();
        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        $this->info('✓ Dummy data deleted');

        // ── STEP 2: IMPORT LECTURERS ───────────────────────────────────────
        $this->importLecturers();

        // ── STEP 3: IMPORT STUDENTS ────────────────────────────────────────
        $this->importFromExcel();
        $this->importFromCsv();

        $this->info('');
        $this->info('✓ Import complete!');
        $this->info('  Lecturers : ' . Lecturer::count());
        $this->info('  Applicants: ' . Applicant::count());
        $this->info('  Students  : ' . Student::count());
        $this->info('  Progress  : ' . StudentProgress::count());
    }

    // ── LECTURERS ────────────────────────────────────────────────────────────
    private function importLecturers(): void
    {
        $this->info('Importing lecturers...');

        $spreadsheet = IOFactory::load(storage_path('app/imports/students.xlsx'));
        $sheet       = $spreadsheet->getSheetByName('Penyelia');
        $rows        = $sheet->toArray();

        // Deduplicate by normalized name
        $seen = [];

        foreach (array_slice($rows, 1) as $row) {
            $name    = trim($row[0] ?? '');
            $staffNo = trim($row[1] ?? '');

            if (empty($name)) continue;

            $normalized = $this->normalizeName($name);
            if (isset($seen[$normalized])) continue;
            $seen[$normalized] = true;

            // Detect external lecturer
            $isExternal  = false;
            $university  = null;
            if (preg_match('/\(([^)]+)\)/', $name, $m)) {
                $isExternal = true;
                $university = $m[1];
                $name       = trim(preg_replace('/\s*\([^)]+\)/', '', $name));
            }

            Lecturer::create([
                'full_name'   => $name,
                'staff_no'    => $staffNo ?: null,
                'is_external' => $isExternal,
                'university'  => $university,
            ]);
        }

        $this->info('✓ Lecturers: ' . Lecturer::count());
    }

    // ── EXCEL IMPORT ─────────────────────────────────────────────────────────
    private function importFromExcel(): void
    {
        $this->info('Importing from Excel...');

        $spreadsheet = IOFactory::load(storage_path('app/imports/students.xlsx'));
        $imported    = 0;

        foreach (['SARJANA' => 'Master', 'PHD' => 'PhD'] as $sheetName => $programType) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $rows  = $sheet->toArray(null, true, true, false);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = array_combine($header, $row);

                // Filter Kampus Kota only
                $campus = strtoupper(trim($data['KAMPUS'] ?? ''));
                if (!str_contains($campus, 'KOTA') || str_contains($campus, 'JELI')) continue;

                // Skip statuses
                $rawStatus = strtoupper(trim($data['STATUS PELAJAR'] ?? ''));
                if (in_array($rawStatus, array_map('strtoupper', $this->skipStatuses))) continue;

                $matricNo = trim($data['NO. MATRIK'] ?? '');
                if (empty($matricNo)) continue;

                // Skip if already imported
                if (Student::where('matric_no', $matricNo)->exists()) continue;

                // Parse intake
                [$session, $month] = $this->parseExcelIntake($data['SESI MASUK'] ?? '');

                // Map status
                $status = $this->statusMap[$rawStatus] ?? 'Active';

                // Map payment
                $payment = $this->mapPayment($data['PEMBIAYAAN SENDIRI / TAJAAN'] ?? '');

                // Map gender
                $gender = $this->mapGender($data['JANTINA'] ?? '');

                // Map country
                $country = $this->mapCountry($data['NEGARA ASAL'] ?? '');

                // Identity
                $identityNo   = trim($data['NO. IC / PASSPORT'] ?? '');
                $identityType = $this->guessIdentityType($identityNo, $country);

                // Create applicant
                $applicant = Applicant::create([
                    'full_name'     => trim($data['NAMA PELAJAR'] ?? ''),
                    'identity_type' => $identityType,
                    'identity_no'   => $identityNo,
                    'email'         => trim($data['EMEL PERSONAL'] ?? '') ?: null,
                    'gender'        => $gender,
                    'program_applied' => $programType,
                    'intake_session'  => $session,
                    'intake_month'    => $month,
                    'prev_edu'       => null,
                    'status'          => 'Approved',
                    'country'         => $country,
                ]);

                // Create student
                $student = Student::create([
                    'applicant_id'   => $applicant->id,
                    'matric_no'      => $matricNo,
                    'email'          => trim($data['EMEL PELAJAR'] ?? '') ?: trim($data['EMEL PERSONAL'] ?? '') ?: null,
                    'program_type'   => $programType,
                    'intake_session' => $session,
                    'intake_month'   => $month,
                    'gender'         => $gender,
                    'country'        => $country,
                    'payment_method' => $payment,
                    'status'         => $status,
                    'nationality_type' => $country === 'Malaysia' ? 'Local' : 'International',
                ]);

                // Assign main supervisor
                $svName = trim($data['PENYELIA UTAMA'] ?? '');
                if ($svName) {
                    $sv = $this->findLecturer($svName);
                    if ($sv) $student->update(['main_sv_id' => $sv->id]);
                }

                // Create progress
                StudentProgress::create([
                    'student_id'                 => $student->id,
                    'eng_test_status'            => 'Pending',
                    'research_method'            => 'Pending',
                    'pd_status'                  => $this->mapPdStatus($data['STATUS PD PELAJAR'] ?? ''),
                    'pre_viva_status'            => 'Pending',
                    'viva_status'                => $this->mapVivaStatus($data['KEPUTUSAN VIVA'] ?? ''),
                    'scholarship_status'         => 'Not Applicable',
                    'tuition_fee_status'         => 'Pending',
                    'progress_report_status'     => 'Pending',
                    'degree_verification_status' => 'Pending',
                    'gdrive_links'               => [],
                ]);

                $imported++;
            }
        }

        $this->info("✓ Excel imported: {$imported} students");
    }

    // ── CSV IMPORT ───────────────────────────────────────────────────────────
    private function importFromCsv(): void
    {
        $this->info('Importing from CSV...');

        $csv     = array_map('str_getcsv', file(storage_path('app/imports/students.csv')));
        $header  = array_shift($csv);
        $imported = 0;

        foreach ($csv as $row) {
            if (count($row) !== count($header)) continue;
            $data = array_combine($header, $row);

            $matricNo = trim($data['Student ID'] ?? '');
            if (empty($matricNo)) continue;

            // Skip if already imported from Excel
            if (Student::where('matric_no', $matricNo)->exists()) continue;

            // Skip unwanted statuses
            $rawStatus = strtoupper(trim($data['Status'] ?? ''));
            if (in_array($rawStatus, array_map('strtoupper', $this->skipStatuses))) continue;

            $status      = $this->statusMap[$rawStatus] ?? null;
            if (!$status) continue;

            $programType = strtoupper(trim($data['Level of Study'] ?? '')) === 'PHD' ? 'PhD' : 'Master';

            [$session, $month] = $this->parseCsvIntake($data['Intake Semester'] ?? '');

            $gender  = $this->mapGender($data['Gender'] ?? '');
            $country = $this->mapCountry($data['Country'] ?? '');

            $identityNo   = trim($data['NRIC'] ?? '');
            $identityType = $this->guessIdentityType($identityNo, $country);

            $applicant = Applicant::create([
                'full_name'       => trim($data['Name'] ?? ''),
                'identity_type'   => $identityType,
                'identity_no'     => $identityNo,
                'email'           => trim($data['Personal Email'] ?? '') ?: null,
                'gender'          => $gender,
                'program_applied' => $programType,
                'intake_session'  => $session,
                'intake_month'    => $month,
                'prev_edu'       => null,
                    'status'          => 'Approved',
                'country'         => $country,
            ]);

            $student = Student::create([
                'applicant_id'     => $applicant->id,
                'matric_no'        => $matricNo,
                'email'            => trim($data['Siswa Mail'] ?? '') ?: null,
                'program_type'     => $programType,
                'intake_session'   => $session,
                'intake_month'     => $month,
                'gender'           => $gender,
                'country'          => $country,
                'payment_method'   => 'Not-stated',
                'status'           => $status,
                'nationality_type' => $country === 'Malaysia' ? 'Local' : 'International',
            ]);

            StudentProgress::create([
                'student_id'                 => $student->id,
                'eng_test_status'            => 'Pending',
                'research_method'            => 'Pending',
                'pd_status'                  => 'Pending',
                'pre_viva_status'            => 'Pending',
                'viva_status'                => 'Pending',
                'scholarship_status'         => 'Not Applicable',
                'tuition_fee_status'         => 'Pending',
                'progress_report_status'     => 'Pending',
                'degree_verification_status' => 'Pending',
                'gdrive_links'               => [],
            ]);

            $imported++;
        }

        $this->info("✓ CSV imported: {$imported} students");
    }

    // ── HELPERS ──────────────────────────────────────────────────────────────

    private function parseExcelIntake(string $sesi): array
    {
        if (empty($sesi)) return [null, 'September'];
        $month   = str_contains(strtolower($sesi), 'februari') ? 'February' : 'September';
        preg_match('/(\d{4}\/\d{4})/', $sesi, $m);
        return [$m[1] ?? null, $month];
    }

    private function parseCsvIntake(string $code): array
    {
        if (empty($code)) return [null, 'September'];
        // e.g. PPSPH20211 = 2021/2022 Sep, PPSPH20212 = 2021/2022 Feb
        preg_match('/(\d{4})(\d)$/', $code, $m);
        if ($m) {
            $year    = (int) $m[1];
            $session = "{$year}/" . ($year + 1);
            $month   = $m[2] === '2' ? 'February' : 'September';
            return [$session, $month];
        }
        return [null, 'September'];
    }

    private function mapGender(string $raw): string
    {
        $raw = strtoupper(trim($raw));
        if (in_array($raw, ['PEREMPUAN', 'FEMALE', 'F'])) return 'Female';
        return 'Male';
    }

    private function mapCountry(string $raw): ?string
    {
        if (empty($raw)) return null;
        $map = [
            'MALAYSIA'    => 'Malaysia',
            'INDONESIA'   => 'Indonesia',
            'PAKISTAN'    => 'Pakistan',
            'CHINA'       => 'China',
            'NIGERIA'     => 'Nigeria',
            'BANGLADESH'  => 'Bangladesh',
            'YEMEN'       => 'Yemen',
            'AFGHANISTAN' => 'Afghanistan',
            'INDIA'       => 'India',
            'IRAN'        => 'Iran',
            'IRAQ'        => 'Iraq',
            'JORDAN'      => 'Jordan',
        ];
        return $map[strtoupper(trim($raw))] ?? ucwords(strtolower(trim($raw)));
    }

    private function mapPayment(string $raw): string
    {
        $raw = strtolower($raw);
        if (str_contains($raw, 'biasiswa') || str_contains($raw, 'geran') ||
            str_contains($raw, 'hlp') || str_contains($raw, 'hlu') ||
            str_contains($raw, 'frgs')) return 'Scholarship';
        if (str_contains($raw, 'sendiri')) return 'Self-funded';
        return 'Not-stated';
    }

    private function mapPdStatus(string $raw): string
    {
        $raw = strtoupper(trim($raw));
        return match($raw) {
            'LULUS', 'PASS', 'PASSED' => 'Passed',
            'MINOR CORRECTION'        => 'Minor Correction',
            'MAJOR CORRECTION'        => 'Major Correction',
            default                   => 'Pending',
        };
    }

    private function mapVivaStatus(string $raw): string
    {
        $raw = strtoupper(trim($raw));
        return match($raw) {
            'LULUS', 'PASS', 'PASSED' => 'Passed',
            'GAGAL', 'FAIL', 'FAILED' => 'Failed',
            default                   => 'Pending',
        };
    }

    private function guessIdentityType(string $identityNo, ?string $country): string
    {
        if ($country === 'Malaysia') return 'IC';
        // IC format: 12 digits
        if (preg_match('/^\d{12}$/', preg_replace('/[-]/', '', $identityNo))) return 'IC';
        return 'Passport';
    }

    private function normalizeName(string $name): string
    {
        return strtolower(preg_replace('/\s+/', ' ', trim($name)));
    }

    private function findLecturer(string $name): ?Lecturer
    {
        $name = trim($name);
        if (empty($name)) return null;

        // Exact match first
        $lect = Lecturer::whereRaw('LOWER(full_name) = ?', [strtolower($name)])->first();
        if ($lect) return $lect;

        // Partial match
        $lect = Lecturer::whereRaw('LOWER(full_name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
        if ($lect) return $lect;

        // Try matching key words (last name)
        $words = explode(' ', $name);
        foreach (array_reverse($words) as $word) {
            if (strlen($word) < 4) continue;
            $lect = Lecturer::whereRaw('LOWER(full_name) LIKE ?', ['%' . strtolower($word) . '%'])->first();
            if ($lect) return $lect;
        }

        return null;
    }
}