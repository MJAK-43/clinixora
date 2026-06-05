<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesReceptionRoomAgent
{
    protected function canManageReceptionRooms(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
