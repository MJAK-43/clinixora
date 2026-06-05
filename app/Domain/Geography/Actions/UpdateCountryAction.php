<?php

namespace App\Domain\Geography\Actions;

use App\Models\Country;

class UpdateCountryAction
{
    /**
     * @param  array{name: string, code: string, flag_code?: string|null, is_active?: bool}  $data
     */
    public function execute(Country $country, array $data): Country
    {
        $code = strtoupper($data['code']);

        $country->update([
            'name' => $data['name'],
            'code' => $code,
            'flag_code' => strtolower($data['flag_code'] ?? $code),
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $country->fresh();
    }
}
