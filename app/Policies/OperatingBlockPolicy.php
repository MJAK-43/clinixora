<?php

namespace App\Policies;

use App\Models\OperatingBlock;
use App\Models\User;
use App\Policies\Concerns\AuthorizesOperatingBlocks;

class OperatingBlockPolicy
{
    use AuthorizesOperatingBlocks;

    public function viewAny(?User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function view(?User $user, OperatingBlock $operatingBlock): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function update(?User $user, OperatingBlock $operatingBlock): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function delete(?User $user, OperatingBlock $operatingBlock): bool
    {
        return $this->canManageOperatingBlocks($user);
    }
}
