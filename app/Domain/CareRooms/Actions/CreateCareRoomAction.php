<?php

namespace App\Domain\CareRooms\Actions;

use App\Models\CareRoom;

class CreateCareRoomAction
{
    /**
     * @param  array{service_id: int, name: string, code: string, location: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): CareRoom
    {
        return CareRoom::query()->create([
            'service_id' => $data['service_id'],
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'location' => $data['location'],
            'icon' => $data['icon'] ?? 'bed',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
