<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesSpecialtyAgent
{
    protected function canManageSpecialties(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
