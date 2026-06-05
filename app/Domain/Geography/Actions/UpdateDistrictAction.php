<?php

namespace App\Domain\Geography\Actions;

use App\Models\District;

class UpdateDistrictAction
{
    /**
     * @param  array{name: string, code: string, is_active?: bool}  $data
     */
    public function execute(District $district, array $data): District
    {
        $district->update([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $district->fresh();
    }
}
