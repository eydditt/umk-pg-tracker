<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'applicant_id', 'matric_no', 'email', 'application_docs_links',
    'program_type', 'intake_session', 'gender', 'nationality_type',
    'country', 'payment_method', 'main_sv_id' , 'status', 'intake_month' ,
    'extended_semesters', 'extension_reason', 'extension_status',
    'extension_requested_at', 'extension_approved_at', 'semester_override',
    ];
    protected $casts = [
        'application_docs_links'  => 'array',
        'extension_requested_at'  => 'datetime',
        'extension_approved_at'   => 'datetime',
    ];

    // ── RELATIONSHIPS ──────────────────────────

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function progress()
    {
        return $this->hasOne(StudentProgress::class);
    }

    public function mainSupervisor()
    {
        return $this->belongsTo(Lecturer::class, 'main_sv_id');
    }

    // NEW — multiple co-supervisors
    public function coSupervisors()
    {
        return $this->belongsToMany(Lecturer::class, 'student_co_supervisors', 'student_id', 'lecturer_id');
    }

    // ── SEMESTER LOGIC ─────────────────────────

    /**
     * Base max semester: Master = 6, PhD = 12
     */
    public function getMaxSemesterAttribute(): int
    {
        return $this->program_type === 'PhD' ? 12 : 6;
    }

    /**
     * Effective max = base max + approved extensions
     */
    public function getEffectiveMaxSemesterAttribute(): int
    {
        $extra = $this->extension_status === 'Approved'
            ? (int) $this->extended_semesters
            : 0;

        return $this->max_semester + $extra;
    }

   
    public function getCurrentSemesterAttribute(): ?int
        {
            if (!$this->intake_session) return null;
            if (in_array($this->status, ['Completed', 'Terminated'])) return null;

            // Manual override
            if (!is_null($this->semester_override)) {
                return $this->semester_override;
            }

            $startYear  = (int) explode('/', $this->intake_session)[0];
            $startMonth = $this->intake_month === 'February' ? 2 : 9;
            $startDate  = Carbon::create($startYear, $startMonth, 1);
            $now        = Carbon::now();

            if ($now->lt($startDate)) return 1;

            $months   = $startDate->diffInMonths($now);
            $semester = (int) floor($months / 6) + 1;

            return min($semester, $this->effective_max_semester + 5);
        }

    /**
     * Is student currently exceeding their effective max semester?
     */
    public function getIsExceededSemesterAttribute(): bool
        {
            if (in_array($this->status, ['Completed', 'Terminated'])) return false;
            $current = $this->current_semester;
            if (!$current) return false;
            return $current > $this->effective_max_semester;
        }

    /**
     * Semester progress as percentage (for display)
     */
    public function getSemesterProgressAttribute(): int
    {
        $current = $this->current_semester;
        if (!$current) return 0;
        return min(100, (int) round(($current / $this->effective_max_semester) * 100));
    }
}