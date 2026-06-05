<?php

namespace App\Domain\Geography\Actions;

use App\Models\City;
use App\Models\Country;

class CreateCityAction
{
    /**
     * @param  array{name: string, code: string, is_active?: bool}  $data
     */
    public function execute(Country $country, array $data): City
    {
        return City::query()->create([
            'country_id' => $country->id,
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
