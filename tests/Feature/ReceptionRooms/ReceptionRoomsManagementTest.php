<?php

namespace Tests\Feature\ReceptionRooms;

use App\Models\ReceptionRoom;
use App\Models\User;
use Database\Seeders\ReceptionRoomSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceptionRoomsManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_reception_rooms_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->seed(ReceptionRoomSeeder::class);

        $this->actingAs($admin)
            ->get(route('reception_rooms.index'))
            ->assertOk()
            ->assertSee('Salles d\'accueil', false)
            ->assertSee('Accueil principal', false)
            ->assertSee('Liste des salles d\'accueil (6)', false);
    }

    public function test_secretary_cannot_access_reception_rooms_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('reception_rooms.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_reception_room(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('reception_rooms.store'), [
                'name' => 'Accueil test',
                'code' => 'ACC-99',
                'location' => 'Hall test',
                'icon' => 'briefcase',
                'is_active' => 1,
            ])
            ->assertRedirect(route('reception_rooms.index'))
            ->assertSessionHas('success');

        $room = ReceptionRoom::query()->where('code', 'ACC-99')->first();
        $this->assertNotNull($room);

        $this->actingAs($admin)
            ->patch(route('reception_rooms.update', $room), [
                'name' => 'Accueil test modifié',
                'code' => 'ACC-98',
                'location' => 'Niveau 1',
                'icon' => 'users',
                'is_active' => 1,
            ])
            ->assertRedirect(route('reception_rooms.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reception_rooms', ['code' => 'ACC-98']);

        $this->actingAs($admin)
            ->delete(route('reception_rooms.destroy', $room->fresh()))
            ->assertRedirect(route('reception_rooms.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('reception_rooms', ['code' => 'ACC-98']);
    }

    public function test_search_and_status_filters_work(): void
    {
        $admin = User::factory()->admin()->create();
        ReceptionRoom::factory()->create(['name' => 'Accueil urgences', 'code' => 'ACC-02']);
        ReceptionRoom::factory()->inactive()->create(['name' => 'Accueil administratif', 'code' => 'ACC-06']);

        $this->actingAs($admin)
            ->get(route('reception_rooms.index', ['search' => 'urgences']))
            ->assertOk()
            ->assertSee('Accueil urgences', false)
            ->assertDontSee('Accueil administratif', false);

        $this->actingAs($admin)
            ->get(route('reception_rooms.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Accueil administratif', false)
            ->assertDontSee('Accueil urgences', false);
    }

    public function test_ajax_panel_returns_table_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        ReceptionRoom::factory()->create(['name' => 'Accueil pédiatrie', 'code' => 'ACC-04']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-ReceptionRoom-Panel' => 'table',
            ])
            ->get(route('reception_rooms.index', ['search' => 'pédiatrie']))
            ->assertOk()
            ->assertSee('Accueil pédiatrie', false)
            ->assertDontSee('Structure &amp; Organisation', false);
    }
}
