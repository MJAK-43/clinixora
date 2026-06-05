<?php

namespace Tests\Feature;

use Database\Seeders\SpecialtySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialtySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_twenty_eight_specialties(): void
    {
        $this->seed(SpecialtySeeder::class);

        $this->assertDatabaseCount('specialties', 28);
        $this->assertDatabaseHas('specialties', ['code' => 'CARD', 'name' => 'Cardiologie', 'is_active' => true]);
        $this->assertDatabaseHas('specialties', ['code' => 'PNEU', 'name' => 'Pneumologie', 'is_active' => false]);
    }
}
