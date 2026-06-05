<?php

namespace App\Domain\OperatingBlocks\Actions;

use App\Models\OperatingBlock;

class CreateOperatingBlockAction
{
    /**
     * @param  array{service_id: int, name: string, code: string, location: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): OperatingBlock
    {
        return OperatingBlock::query()->create([
            'service_id' => $data['service_id'],
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'location' => $data['location'],
            'icon' => $data['icon'] ?? 'scalpel',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
