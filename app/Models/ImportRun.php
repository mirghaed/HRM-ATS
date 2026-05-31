<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'connector_id',
        'status',
        'total_items',
        'created_items',
        'updated_items',
        'duplicate_items',
        'failed_items',
        'meta',
        'error_message',
        'created_by',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ApplicationSource::class, 'source_id');
    }

    public function connector(): BelongsTo
    {
        return $this->belongsTo(SourceConnector::class, 'connector_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ImportItem::class);
    }
}