<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'question_id',
        'answer_text',
        'answer_json',
        'file_id',
    ];

    protected function casts(): array
    {
        return [
            'answer_json' => 'array',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(JobPositionQuestion::class, 'question_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CandidateFile::class, 'file_id');
    }
}