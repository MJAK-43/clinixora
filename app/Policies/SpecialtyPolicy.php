<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;
use App\Policies\Concerns\AuthorizesSpecialties;

class SpecialtyPolicy
{
    use AuthorizesSpecialties;

    public function viewAny(?User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function view(?User $user, Specialty $specialty): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function update(?User $user, Specialty $specialty): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function delete(?User $user, Specialty $specialty): bool
    {
        return $this->canManageSpecialties($user);
    }
}
