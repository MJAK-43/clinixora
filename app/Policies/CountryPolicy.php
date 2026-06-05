<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use App\Policies\Concerns\AuthorizesGeography;

class CountryPolicy
{
    use AuthorizesGeography;

    public function viewAny(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function view(?User $user, Country $country): bool
    {
        return $this->canManageGeography($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function update(?User $user, Country $country): bool
    {
        return $this->canManageGeography($user);
    }

    public function delete(?User $user, Country $country): bool
    {
        return $this->canManageGeography($user);
    }
}
