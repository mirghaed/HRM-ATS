<?php

namespace App\Policies;

use App\Models\JobPosition;
use App\Models\User;

class JobPositionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('job_positions.view');
    }

    public function view(User $user, JobPosition $jobPosition): bool
    {
        return $user->can('job_positions.view');
    }

    public function create(User $user): bool
    {
        return $user->can('job_positions.create');
    }

    public function update(User $user, JobPosition $jobPosition): bool
    {
        return $user->can('job_positions.update');
    }

    public function delete(User $user, JobPosition $jobPosition): bool
    {
        return $user->can('job_positions.delete');
    }
}