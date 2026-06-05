<?php

namespace App\Policies;

use App\Models\AssistantSetting;
use App\Models\User;

class AssistantSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ?AssistantSetting $setting = null): bool
    {
        return $user->isAdmin();
    }
}
