<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPositionQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_position_id',
        'question',
        'type',
        'options',
        'placeholder',
        'help_text',
        'validation_rules',
        'is_required',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'validation_rules' => 'array',
            'is_required' => 'boolean',
        ];
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ApplicationAnswer::class, 'question_id');
    }
}