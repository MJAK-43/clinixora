<?php

namespace Database\Factories;

use App\Models\ReceptionRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReceptionRoom>
 */
class ReceptionRoomFactory extends Factory
{
    protected $model = ReceptionRoom::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Accueil '.fake()->unique()->word(),
            'code' => 'ACC-'.str_pad((string) fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT),
            'location' => fake()->randomElement(['Hall d\'entrée', 'Niveau 0', 'Niveau 1']),
            'icon' => 'briefcase',
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
