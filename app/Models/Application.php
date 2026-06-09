<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tracking_code',
        'candidate_id',
        'job_position_id',
        'department_id',
        'source_id',
        'source_reference',
        'source_profile_url',
        'current_status_id',
        'expected_salary_min',
        'expected_salary_max',
        'salary_fit_score',
        'skills_fit_score',
        'experience_fit_score',
        'overall_score',
        'cover_letter',
        'form_answers',
        'raw_payload',
        'assigned_recruiter_id',
        'assigned_department_manager_id',
        'assigned_interviewer_id',
        'rejection_reason_id',
        'is_duplicate',
        'duplicate_of_application_id',
        'applied_at',
        'last_status_changed_at',
    ];

    protected function casts(): array
    {
        return [
            'form_answers' => 'array',
            'raw_payload' => 'array',
            'is_duplicate' => 'boolean',
            'applied_at' => 'datetime',
            'last_status_changed_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ApplicationSource::class, 'source_id');
    }

    public function currentStatus(): BelongsTo
    {
        return $this->belongsTo(RecruitmentStatus::class, 'current_status_id');
    }

    public function rejectionReason(): BelongsTo
    {
        return $this->belongsTo(RejectionReason::class);
    }

    public function duplicateOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'duplicate_of_application_id');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_recruiter_id');
    }

    public function departmentManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_department_manager_id');
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_interviewer_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ApplicationAnswer::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(CandidateFile::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ApplicationNote::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(HrmActivityLog::class);
    }

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'application_tag')->withTimestamps();
    }
}