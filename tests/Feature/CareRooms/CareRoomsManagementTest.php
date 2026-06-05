<?php

namespace Tests\Feature\CareRooms;

use App\Models\CareRoom;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\CareRoomSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareRoomsManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_care_rooms_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->seed(ServiceSeeder::class);
        $this->seed(CareRoomSeeder::class);

        $this->actingAs($admin)
            ->get(route('care_rooms.index'))
            ->assertOk()
            ->assertSee('Salles de soin', false)
            ->assertSee('Salle de soins générale', false)
            ->assertSee('Liste des salles de soin (8)', false);
    }

    public function test_secretary_cannot_access_care_rooms_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('care_rooms.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_care_room(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Consultation générale', 'code' => 'CONS']);

        $this->actingAs($admin)
            ->post(route('care_rooms.store'), [
                'service_id' => $service->id,
                'name' => 'Salle test',
                'code' => 'SOIN-99',
                'location' => 'RDC',
                'icon' => 'bed',
                'is_active' => 1,
            ])
            ->assertRedirect(route('care_rooms.index'))
            ->assertSessionHas('success');

        $room = CareRoom::query()->where('code', 'SOIN-99')->first();
        $this->assertNotNull($room);

        $this->actingAs($admin)
            ->patch(route('care_rooms.update', $room), [
                'service_id' => $service->id,
                'name' => 'Salle test modifiée',
                'code' => 'SOIN-98',
                'location' => 'Niveau 1',
                'icon' => 'stethoscope',
                'is_active' => 1,
            ])
            ->assertRedirect(route('care_rooms.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('care_rooms', ['code' => 'SOIN-98']);

        $this->actingAs($admin)
            ->delete(route('care_rooms.destroy', $room->fresh()))
            ->assertRedirect(route('care_rooms.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('care_rooms', ['code' => 'SOIN-98']);
    }

    public function test_search_status_and_service_filters_work(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Urgences', 'code' => 'URG']);
        $other = Service::factory()->create(['name' => 'Oncologie', 'code' => 'ONCS']);

        CareRoom::factory()->for($service)->create(['name' => 'Salle urgence', 'code' => 'SOIN-03']);
        CareRoom::factory()->for($other)->inactive()->create(['name' => 'Salle oncologie', 'code' => 'SOIN-08']);

        $this->actingAs($admin)
            ->get(route('care_rooms.index', ['search' => 'urgence']))
            ->assertOk()
            ->assertSee('Salle urgence', false)
            ->assertDontSee('Salle oncologie', false);

        $this->actingAs($admin)
            ->get(route('care_rooms.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Salle oncologie', false)
            ->assertDontSee('Salle urgence', false);
    }

    public function test_ajax_panel_returns_table_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        CareRoom::factory()->for($service)->create(['name' => 'Salle dialyse', 'code' => 'SOIN-07']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-CareRoom-Panel' => 'table',
            ])
            ->get(route('care_rooms.index', ['search' => 'dialyse']))
            ->assertOk()
            ->assertSee('Salle dialyse', false)
            ->assertDontSee('Structure &amp; Organisation', false);
    }
}
