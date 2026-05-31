<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentStatusTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_status_id',
        'to_status_id',
        'allowed_roles',
        'requires_note',
        'requires_interview',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'allowed_roles' => 'array',
            'requires_note' => 'boolean',
            'requires_interview' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(RecruitmentStatus::class, 'from_status_id');
    }

    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(RecruitmentStatus::class, 'to_status_id');
    }
}