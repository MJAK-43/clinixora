<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesServiceAgent
{
    protected function canManageServices(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
