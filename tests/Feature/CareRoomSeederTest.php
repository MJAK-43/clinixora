<?php

namespace Tests\Feature;

use Database\Seeders\CareRoomSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareRoomSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_eight_care_rooms(): void
    {
        $this->seed(ServiceSeeder::class);
        $this->seed(CareRoomSeeder::class);

        $this->assertDatabaseCount('care_rooms', 8);
        $this->assertDatabaseHas('care_rooms', ['code' => 'SOIN-01', 'name' => 'Salle de soins générale', 'is_active' => true]);
        $this->assertDatabaseHas('care_rooms', ['code' => 'SOIN-08', 'name' => 'Salle de soins oncologie', 'is_active' => false]);
    }
}
