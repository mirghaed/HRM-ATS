<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'description',
        'color',
        'sort_order',
        'is_default',
        'is_terminal',
        'is_success',
        'requires_note',
        'can_schedule_interview',
        'sms_template_id',
        'notify_candidate',
        'notify_department_manager',
        'notify_interviewer',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_terminal' => 'boolean',
            'is_success' => 'boolean',
            'requires_note' => 'boolean',
            'can_schedule_interview' => 'boolean',
            'notify_candidate' => 'boolean',
            'notify_department_manager' => 'boolean',
            'notify_interviewer' => 'boolean',
        ];
    }

    public function smsTemplate(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    public function transitionsFrom(): HasMany
    {
        return $this->hasMany(RecruitmentStatusTransition::class, 'from_status_id');
    }

    public function transitionsTo(): HasMany
    {
        return $this->hasMany(RecruitmentStatusTransition::class, 'to_status_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'current_status_id');
    }
}
