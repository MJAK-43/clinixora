<?php

namespace Tests\Feature;

use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_twenty_eight_services(): void
    {
        $this->seed(ServiceSeeder::class);

        $this->assertDatabaseCount('services', 28);
        $this->assertDatabaseHas('services', ['code' => 'CONS', 'name' => 'Consultation générale', 'is_active' => true]);
        $this->assertDatabaseHas('services', ['code' => 'ARCH', 'name' => 'Archives médicales', 'is_active' => false]);
    }
}
