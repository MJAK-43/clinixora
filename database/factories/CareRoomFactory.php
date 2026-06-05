<?php

namespace Database\Factories;

use App\Models\CareRoom;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CareRoom>
 */
class CareRoomFactory extends Factory
{
    protected $model = CareRoom::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => 'Salle '.fake()->unique()->word(),
            'code' => 'SOIN-'.str_pad((string) fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT),
            'location' => 'Niveau '.fake()->numberBetween(0, 3),
            'icon' => 'bed',
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
