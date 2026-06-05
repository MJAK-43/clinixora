<?php

namespace App\Domain\ReceptionRooms\Actions;

use App\Models\ReceptionRoom;

class DeleteReceptionRoomAction
{
    public function execute(ReceptionRoom $room): void
    {
        $room->delete();
    }
}
