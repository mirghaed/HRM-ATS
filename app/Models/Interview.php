<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'candidate_id',
        'job_position_id',
        'department_id',
        'interviewer_id',
        'scheduled_by',
        'type',
        'status',
        'start_at',
        'end_at',
        'location_title',
        'address',
        'online_meeting_url',
        'description',
        'score',
        'result_note',
        'send_sms_to_candidate',
        'candidate_sms_sent_at',
        'reminder_sms_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'send_sms_to_candidate' => 'boolean',
            'candidate_sms_sent_at' => 'datetime',
            'reminder_sms_sent_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
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

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function scheduler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(InterviewReminder::class);
    }
}