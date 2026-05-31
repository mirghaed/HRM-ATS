<?php

namespace App\Policies;

use App\Models\HrmSetting;
use App\Models\User;

class HrmSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.view');
    }

    public function view(User $user, HrmSetting $hrmSetting): bool
    {
        return $user->can('settings.view');
    }

    public function update(User $user, HrmSetting $hrmSetting): bool
    {
        return $user->can('settings.manage');
    }
}