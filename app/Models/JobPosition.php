<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPosition extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'department_id',
        'title',
        'slug',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'employment_type',
        'work_mode',
        'location',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_salary_visible_public',
        'status',
        'is_public',
        'default_recruiter_id',
        'default_interviewer_id',
        'capacity',
        'priority',
        'opened_at',
        'closed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_salary_visible_public' => 'boolean',
            'is_public' => 'boolean',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_recruiter_id');
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_interviewer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(JobPositionQuestion::class);
    }

    public function interviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'job_position_interviewer')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function requiredSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_position_skill')
            ->withPivot(['is_required', 'weight', 'min_level', 'min_years_experience'])
            ->withTimestamps();
    }

    public function plainTextSummary(?int $limit = null): string
    {
        $html = (string) ($this->description ?: $this->requirements ?: '');
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');

        if ($limit === null) {
            return $text;
        }

        return Str::limit($text, $limit);
    }
}