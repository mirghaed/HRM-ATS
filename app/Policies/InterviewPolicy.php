<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('interviews.view_all') || $user->can('interviews.view_department') || $user->can('interviews.view_own');
    }

    public function view(User $user, Interview $interview): bool
    {
        if ($user->can('interviews.view_all')) {
            return true;
        }

        if ($user->can('interviews.view_own') && $interview->interviewer_id === $user->id) {
            return true;
        }

        if ($user->can('interviews.view_department')) {
            return $user->departments()->where('departments.id', $interview->department_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('interviews.create');
    }

    public function update(User $user, Interview $interview): bool
    {
        return $user->can('interviews.update');
    }

    public function delete(User $user, Interview $interview): bool
    {
        return $user->can('interviews.cancel');
    }
}