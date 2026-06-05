<?php

namespace App\Domain\CareRooms\Actions;

use App\Models\CareRoom;

class UpdateCareRoomAction
{
    /**
     * @param  array{service_id: int, name: string, code: string, location: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(CareRoom $room, array $data): CareRoom
    {
        $room->update([
            'service_id' => $data['service_id'],
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'location' => $data['location'],
            'icon' => $data['icon'] ?? $room->icon,
            'is_active' => $data['is_active'] ?? $room->is_active,
        ]);

        return $room->fresh(['service']);
    }
}
