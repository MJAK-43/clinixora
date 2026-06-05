<?php

namespace App\Domain\Geography\Actions;

use App\Models\Country;

class CreateCountryAction
{
    /**
     * @param  array{name: string, code: string, flag_code?: string|null, is_active?: bool}  $data
     */
    public function execute(array $data): Country
    {
        $code = strtoupper($data['code']);

        return Country::query()->create([
            'name' => $data['name'],
            'code' => $code,
            'flag_code' => strtolower($data['flag_code'] ?? $code),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
