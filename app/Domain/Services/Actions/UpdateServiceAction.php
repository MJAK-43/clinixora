<?php

namespace App\Domain\Services\Actions;

use App\Models\Service;

class UpdateServiceAction
{
    /**
     * @param  array{name?: string, code?: string, description?: string|null, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(Service $service, array $data): Service
    {
        $service->fill([
            'name' => $data['name'] ?? $service->name,
            'code' => isset($data['code']) ? strtoupper($data['code']) : $service->code,
            'description' => array_key_exists('description', $data) ? $data['description'] : $service->description,
            'icon' => $data['icon'] ?? $service->icon,
            'is_active' => $data['is_active'] ?? $service->is_active,
        ]);

        $service->save();

        return $service->fresh();
    }
}
