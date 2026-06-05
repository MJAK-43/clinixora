<?php

namespace App\Domain\CareRooms\Actions;

use App\Models\CareRoom;

class DeleteCareRoomAction
{
    public function execute(CareRoom $room): void
    {
        $room->delete();
    }
}
