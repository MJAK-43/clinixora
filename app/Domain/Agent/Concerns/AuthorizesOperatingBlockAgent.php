<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesOperatingBlockAgent
{
    protected function canManageOperatingBlocks(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
