<?php

namespace App\Domain\ReceptionRooms\Actions;

use App\Models\ReceptionRoom;

class UpdateReceptionRoomAction
{
    /**
     * @param  array{name: string, code: string, location: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(ReceptionRoom $room, array $data): ReceptionRoom
    {
        $room->update([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'location' => $data['location'],
            'icon' => $data['icon'] ?? $room->icon,
            'is_active' => $data['is_active'] ?? $room->is_active,
        ]);

        return $room->fresh();
    }
}
