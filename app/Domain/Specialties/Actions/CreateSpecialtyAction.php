<?php

namespace App\Domain\Specialties\Actions;

use App\Models\Specialty;

class CreateSpecialtyAction
{
    /**
     * @param  array{name: string, code: string, description?: string|null, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): Specialty
    {
        return Specialty::query()->create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'stethoscope',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
