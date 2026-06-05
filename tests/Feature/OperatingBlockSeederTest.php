<?php

namespace Tests\Feature;

use Database\Seeders\OperatingBlockSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatingBlockSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_nine_operating_blocks(): void
    {
        $this->seed(ServiceSeeder::class);
        $this->seed(OperatingBlockSeeder::class);

        $this->assertDatabaseCount('operating_blocks', 9);
        $this->assertDatabaseHas('operating_blocks', ['code' => 'BLOC-01', 'name' => 'Bloc Central', 'is_active' => true]);
        $this->assertDatabaseHas('operating_blocks', ['code' => 'BLOC-08', 'name' => 'Bloc Urgences', 'is_active' => false]);
    }
}
