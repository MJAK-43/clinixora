<?php

namespace App\Domain\Specialties\Actions;

use App\Models\Specialty;

class UpdateSpecialtyAction
{
    /**
     * @param  array{name?: string, code?: string, description?: string|null, icon?: string|null, is_active?: bool}  $data
     */
    public function execute(Specialty $specialty, array $data): Specialty
    {
        $specialty->fill([
            'name' => $data['name'] ?? $specialty->name,
            'code' => isset($data['code']) ? strtoupper($data['code']) : $specialty->code,
            'description' => array_key_exists('description', $data) ? $data['description'] : $specialty->description,
            'icon' => $data['icon'] ?? $specialty->icon,
            'is_active' => $data['is_active'] ?? $specialty->is_active,
        ]);

        $specialty->save();

        return $specialty->fresh();
    }
}
