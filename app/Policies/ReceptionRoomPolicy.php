<?php

namespace App\Policies;

use App\Models\ReceptionRoom;
use App\Models\User;
use App\Policies\Concerns\AuthorizesReceptionRooms;

class ReceptionRoomPolicy
{
    use AuthorizesReceptionRooms;

    public function viewAny(?User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function view(?User $user, ReceptionRoom $receptionRoom): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function create(?User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function update(?User $user, ReceptionRoom $receptionRoom): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function delete(?User $user, ReceptionRoom $receptionRoom): bool
    {
        return $this->canManageReceptionRooms($user);
    }
}
