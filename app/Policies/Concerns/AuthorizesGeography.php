<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AuthorizesGeography
{
    protected function canManageGeography(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
