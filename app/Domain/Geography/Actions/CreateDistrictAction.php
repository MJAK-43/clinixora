<?php

namespace App\Domain\Geography\Actions;

use App\Models\City;
use App\Models\District;

class CreateDistrictAction
{
    /**
     * @param  array{name: string, code: string, is_active?: bool}  $data
     */
    public function execute(City $city, array $data): District
    {
        return District::query()->create([
            'city_id' => $city->id,
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
