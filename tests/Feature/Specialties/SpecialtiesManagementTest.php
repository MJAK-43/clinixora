<?php

namespace Tests\Feature\Specialties;

use App\Models\Specialty;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SpecialtySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialtiesManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_specialties_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->seed(SpecialtySeeder::class);

        $this->actingAs($admin)
            ->get(route('specialties.index'))
            ->assertOk()
            ->assertSee('Spécialités', false)
            ->assertSee('Cardiologie', false)
            ->assertSee('Liste des spécialités (28)', false);
    }

    public function test_secretary_cannot_access_specialties_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('specialties.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_specialty(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('specialties.store'), [
                'name' => 'Testologie',
                'code' => 'TEST',
                'description' => 'Spécialité de test',
                'icon' => 'stethoscope',
                'is_active' => 1,
            ])
            ->assertRedirect(route('specialties.index'))
            ->assertSessionHas('success');

        $specialty = Specialty::query()->where('code', 'TEST')->first();
        $this->assertNotNull($specialty);

        $this->actingAs($admin)
            ->patch(route('specialties.update', $specialty), [
                'name' => 'Testologie avancée',
                'code' => 'TST2',
                'description' => 'Mise à jour',
                'icon' => 'heart',
                'is_active' => 1,
            ])
            ->assertRedirect(route('specialties.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('specialties', ['code' => 'TST2', 'name' => 'Testologie avancée']);

        $this->actingAs($admin)
            ->delete(route('specialties.destroy', $specialty->fresh()))
            ->assertRedirect(route('specialties.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('specialties', ['code' => 'TST2']);
    }

    public function test_search_and_status_filters_work(): void
    {
        $admin = User::factory()->admin()->create();
        Specialty::factory()->create(['name' => 'Cardiologie', 'code' => 'CARD', 'is_active' => true]);
        Specialty::factory()->create(['name' => 'Pneumologie', 'code' => 'PNEU', 'is_active' => false]);

        $this->actingAs($admin)
            ->get(route('specialties.index', ['search' => 'Cardio']))
            ->assertOk()
            ->assertSee('Cardiologie', false)
            ->assertDontSee('Pneumologie', false);

        $this->actingAs($admin)
            ->get(route('specialties.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Pneumologie', false)
            ->assertDontSee('Cardiologie', false);
    }

    public function test_ajax_panel_returns_table_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        Specialty::factory()->create(['name' => 'Neurologie', 'code' => 'NEUR']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-Specialty-Panel' => 'table',
            ])
            ->get(route('specialties.index', ['search' => 'Neur']))
            ->assertOk()
            ->assertSee('Neurologie', false)
            ->assertDontSee('Structure &amp; Organisation', false);
    }

    public function test_code_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Specialty::factory()->create(['code' => 'DUP']);

        $this->actingAs($admin)
            ->post(route('specialties.store'), [
                'name' => 'Doublon',
                'code' => 'DUP',
            ])
            ->assertSessionHasErrors('code');
    }
}
