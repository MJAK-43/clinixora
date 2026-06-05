<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AuthorizesSpecialties
{
    protected function canManageSpecialties(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
