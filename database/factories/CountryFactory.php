<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        $code = strtoupper(fake()->unique()->lexify('??'));

        return [
            'name' => fake()->country(),
            'code' => $code,
            'flag_code' => strtolower($code),
            'is_active' => true,
        ];
    }
}
