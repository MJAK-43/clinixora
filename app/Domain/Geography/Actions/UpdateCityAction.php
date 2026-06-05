<?php

namespace App\Domain\Geography\Actions;

use App\Models\City;

class UpdateCityAction
{
    /**
     * @param  array{name: string, code: string, is_active?: bool}  $data
     */
    public function execute(City $city, array $data): City
    {
        $city->update([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $city->fresh();
    }
}
