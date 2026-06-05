<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use App\Policies\Concerns\AuthorizesGeography;

class CityPolicy
{
    use AuthorizesGeography;

    public function viewAny(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function view(?User $user, City $city): bool
    {
        return $this->canManageGeography($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function update(?User $user, City $city): bool
    {
        return $this->canManageGeography($user);
    }

    public function delete(?User $user, City $city): bool
    {
        return $this->canManageGeography($user);
    }
}
