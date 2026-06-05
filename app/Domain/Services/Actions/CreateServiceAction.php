<?php

namespace App\Domain\Services\Actions;

use App\Models\Service;

class CreateServiceAction
{
    /**
     * @param  array{name: string, code: string, description?: string|null, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): Service
    {
        return Service::query()->create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'briefcase',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
