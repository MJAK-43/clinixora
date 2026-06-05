<?php

namespace App\Domain\OperatingBlocks\Actions;

use App\Models\OperatingBlock;

class UpdateOperatingBlockAction
{
    /**
     * @param  array{service_id?: int, name?: string, code?: string, location?: string, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(OperatingBlock $block, array $data): OperatingBlock
    {
        $block->fill([
            'service_id' => $data['service_id'] ?? $block->service_id,
            'name' => $data['name'] ?? $block->name,
            'code' => isset($data['code']) ? strtoupper($data['code']) : $block->code,
            'location' => $data['location'] ?? $block->location,
            'icon' => $data['icon'] ?? $block->icon,
            'is_active' => $data['is_active'] ?? $block->is_active,
        ]);

        $block->save();

        return $block->fresh(['service']);
    }
}
