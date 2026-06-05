<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Policies\Concerns\AuthorizesServices;

class ServicePolicy
{
    use AuthorizesServices;

    public function viewAny(?User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function view(?User $user, Service $service): bool
    {
        return $this->canManageServices($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function update(?User $user, Service $service): bool
    {
        return $this->canManageServices($user);
    }

    public function delete(?User $user, Service $service): bool
    {
        return $this->canManageServices($user);
    }
}
