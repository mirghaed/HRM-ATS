<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'key',
        'provider',
        'provider_template_id',
        'body_preview',
        'variables',
        'is_active',
        'auto_send',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
            'auto_send' => 'boolean',
        ];
    }
}