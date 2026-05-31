<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'avatar_path',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'manager_user_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function assignedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'assigned_recruiter_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }
}
