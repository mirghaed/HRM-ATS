<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SourceConnector extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'driver',
        'mode',
        'status',
        'endpoint_url',
        'encrypted_config',
        'last_sync_at',
        'last_error',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'last_sync_at' => 'datetime',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ApplicationSource::class, 'source_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(ImportRun::class, 'connector_id');
    }
}