<?php

namespace App\Policies;

use App\Models\District;
use App\Models\User;
use App\Policies\Concerns\AuthorizesGeography;

class DistrictPolicy
{
    use AuthorizesGeography;

    public function viewAny(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function view(?User $user, District $district): bool
    {
        return $this->canManageGeography($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function update(?User $user, District $district): bool
    {
        return $this->canManageGeography($user);
    }

    public function delete(?User $user, District $district): bool
    {
        return $this->canManageGeography($user);
    }
}
