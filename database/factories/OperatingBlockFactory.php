<?php

namespace Database\Factories;

use App\Models\OperatingBlock;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OperatingBlock>
 */
class OperatingBlockFactory extends Factory
{
    protected $model = OperatingBlock::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => 'Bloc '.fake()->unique()->word(),
            'code' => 'BLOC-'.str_pad((string) fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT),
            'location' => 'Niveau '.fake()->numberBetween(0, 3),
            'icon' => 'scalpel',
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
