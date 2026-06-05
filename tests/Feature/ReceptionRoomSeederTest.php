<?php

namespace Tests\Feature;

use Database\Seeders\ReceptionRoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceptionRoomSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_six_reception_rooms(): void
    {
        $this->seed(ReceptionRoomSeeder::class);

        $this->assertDatabaseCount('reception_rooms', 6);
        $this->assertDatabaseHas('reception_rooms', ['code' => 'ACC-01', 'name' => 'Accueil principal', 'is_active' => true]);
        $this->assertDatabaseHas('reception_rooms', ['code' => 'ACC-06', 'name' => 'Accueil administratif', 'is_active' => false]);
    }
}
