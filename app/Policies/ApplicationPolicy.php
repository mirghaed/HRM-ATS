<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('applications.view_all') || $user->can('applications.view_department') || $user->can('applications.view_assigned');
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->can('applications.view_all')) {
            return true;
        }

        if ($user->can('applications.view_assigned') && $application->assigned_recruiter_id === $user->id) {
            return true;
        }

        if ($user->can('applications.view_department')) {
            return $user->departments()->where('departments.id', $application->department_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('applications.create');
    }

    public function update(User $user, Application $application): bool
    {
        return $user->can('applications.update');
    }

    public function delete(User $user, Application $application): bool
    {
        return $user->can('applications.delete');
    }
}