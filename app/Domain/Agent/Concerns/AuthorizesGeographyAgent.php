<?php

namespace App\Domain\Agent\Concerns;

use App\Models\User;

trait AuthorizesGeographyAgent
{
    protected function canManageGeography(User $user): bool
    {
        return $user->isAdmin() || $user->role?->slug === 'director';
    }
}
