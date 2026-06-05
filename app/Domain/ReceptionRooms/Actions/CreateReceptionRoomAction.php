<?php

namespace App\Domain\ReceptionRooms\Actions;

use App\Models\ReceptionRoom;

class CreateReceptionRoomAction
{
    /**
     * @param  array{name: string, code: string, location: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): ReceptionRoom
    {
        return ReceptionRoom::query()->create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'location' => $data['location'],
            'icon' => $data['icon'] ?? 'briefcase',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
