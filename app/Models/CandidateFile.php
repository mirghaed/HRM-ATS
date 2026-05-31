<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'application_id',
        'type',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'parsed_text',
        'parsed_json',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'parsed_json' => 'array',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}