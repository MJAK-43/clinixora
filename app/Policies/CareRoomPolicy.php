<?php

namespace App\Policies;

use App\Models\CareRoom;
use App\Models\User;
use App\Policies\Concerns\AuthorizesCareRooms;

class CareRoomPolicy
{
    use AuthorizesCareRooms;

    public function viewAny(?User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function view(?User $user, CareRoom $careRoom): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function update(?User $user, CareRoom $careRoom): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function delete(?User $user, CareRoom $careRoom): bool
    {
        return $this->canManageCareRooms($user);
    }
}
