<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AuthorizesReceptionRooms
{
    protected function canManageReceptionRooms(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
