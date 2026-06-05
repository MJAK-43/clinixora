<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesCareRoomAgent
{
    protected function canManageCareRooms(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
