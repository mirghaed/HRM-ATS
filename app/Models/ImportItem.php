<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_run_id',
        'application_id',
        'row_number',
        'external_reference',
        'status',
        'raw_payload',
        'normalized_payload',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'normalized_payload' => 'array',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(ImportRun::class, 'import_run_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}