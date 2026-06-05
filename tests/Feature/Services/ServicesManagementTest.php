<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_services_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->seed(ServiceSeeder::class);

        $this->actingAs($admin)
            ->get(route('services.index'))
            ->assertOk()
            ->assertSee('Services', false)
            ->assertSee('Consultation générale', false)
            ->assertSee('Liste des services (29)', false);
    }

    public function test_secretary_cannot_access_services_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('services.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_service(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('services.store'), [
                'name' => 'Service test',
                'code' => 'TST',
                'description' => 'Description test',
                'icon' => 'briefcase',
                'is_active' => 1,
            ])
            ->assertRedirect(route('services.index'))
            ->assertSessionHas('success');

        $service = Service::query()->where('code', 'TST')->first();
        $this->assertNotNull($service);

        $this->actingAs($admin)
            ->patch(route('services.update', $service), [
                'name' => 'Service test modifié',
                'code' => 'TST2',
                'description' => 'Mise à jour',
                'icon' => 'heart',
                'is_active' => 1,
            ])
            ->assertRedirect(route('services.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('services', ['code' => 'TST2', 'name' => 'Service test modifié']);

        $this->actingAs($admin)
            ->delete(route('services.destroy', $service->fresh()))
            ->assertRedirect(route('services.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('services', ['code' => 'TST2']);
    }

    public function test_search_and_status_filters_work(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Urgences', 'code' => 'URG', 'is_active' => true]);
        Service::factory()->create(['name' => 'Archives médicales', 'code' => 'ARCH', 'is_active' => false]);

        $this->actingAs($admin)
            ->get(route('services.index', ['search' => 'Urg']))
            ->assertOk()
            ->assertSee('Urgences', false)
            ->assertDontSee('Archives médicales', false);

        $this->actingAs($admin)
            ->get(route('services.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Archives médicales', false)
            ->assertDontSee('Urgences', false);
    }

    public function test_ajax_panel_returns_table_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Laboratoire', 'code' => 'LABO']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-Service-Panel' => 'table',
            ])
            ->get(route('services.index', ['search' => 'Labo']))
            ->assertOk()
            ->assertSee('Laboratoire', false)
            ->assertDontSee('Structure &amp; Organisation', false);
    }

    public function test_code_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['code' => 'DUP']);

        $this->actingAs($admin)
            ->post(route('services.store'), [
                'name' => 'Doublon',
                'code' => 'DUP',
            ])
            ->assertSessionHasErrors('code');
    }
}
