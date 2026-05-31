<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\User;

class CandidatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('candidates.view');
    }

    public function view(User $user, Candidate $candidate): bool
    {
        return $user->can('candidates.view');
    }

    public function update(User $user, Candidate $candidate): bool
    {
        return $user->can('candidates.update');
    }
}