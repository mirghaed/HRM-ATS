<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'type',
        'supports_auto_import',
        'is_active',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'supports_auto_import' => 'boolean',
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    public function connectors(): HasMany
    {
        return $this->hasMany(SourceConnector::class, 'source_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'source_id');
    }

    public function importRuns(): HasMany
    {
        return $this->hasMany(ImportRun::class, 'source_id');
    }
}