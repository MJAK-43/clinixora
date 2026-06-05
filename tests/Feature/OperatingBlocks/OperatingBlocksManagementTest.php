<?php

namespace Tests\Feature\OperatingBlocks;

use App\Models\OperatingBlock;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\OperatingBlockSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatingBlocksManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_operating_blocks_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->seed(ServiceSeeder::class);
        $this->seed(OperatingBlockSeeder::class);

        $this->actingAs($admin)
            ->get(route('operating_blocks.index'))
            ->assertOk()
            ->assertSee('Bloc opératoire', false)
            ->assertSee('Bloc Central', false)
            ->assertSee('Liste des blocs opératoires (9)', false);
    }

    public function test_secretary_cannot_access_operating_blocks_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('operating_blocks.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_operating_block(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Chirurgie générale', 'code' => 'CHIR']);

        $this->actingAs($admin)
            ->post(route('operating_blocks.store'), [
                'service_id' => $service->id,
                'name' => 'Bloc test',
                'code' => 'BLOC-99',
                'location' => 'Niveau 0',
                'icon' => 'scalpel',
                'is_active' => 1,
            ])
            ->assertRedirect(route('operating_blocks.index'))
            ->assertSessionHas('success');

        $block = OperatingBlock::query()->where('code', 'BLOC-99')->first();
        $this->assertNotNull($block);

        $this->actingAs($admin)
            ->patch(route('operating_blocks.update', $block), [
                'service_id' => $service->id,
                'name' => 'Bloc test modifié',
                'code' => 'BLOC-98',
                'location' => 'Niveau 1',
                'icon' => 'heart',
                'is_active' => 1,
            ])
            ->assertRedirect(route('operating_blocks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('operating_blocks', ['code' => 'BLOC-98', 'name' => 'Bloc test modifié']);

        $this->actingAs($admin)
            ->delete(route('operating_blocks.destroy', $block->fresh()))
            ->assertRedirect(route('operating_blocks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('operating_blocks', ['code' => 'BLOC-98']);
    }

    public function test_search_status_and_service_filters_work(): void
    {
        $admin = User::factory()->admin()->create();
        $activeService = Service::factory()->create(['name' => 'Urgences', 'code' => 'URG']);
        $otherService = Service::factory()->create(['name' => 'Réanimation', 'code' => 'REA']);

        OperatingBlock::factory()->for($activeService)->create(['name' => 'Bloc Urgences', 'code' => 'BLOC-08', 'is_active' => true]);
        OperatingBlock::factory()->for($otherService)->inactive()->create(['name' => 'Bloc Réanimation', 'code' => 'BLOC-09']);

        $this->actingAs($admin)
            ->get(route('operating_blocks.index', ['search' => 'Urg']))
            ->assertOk()
            ->assertSee('Bloc Urgences', false)
            ->assertDontSee('Bloc Réanimation', false);

        $this->actingAs($admin)
            ->get(route('operating_blocks.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Bloc Réanimation', false)
            ->assertDontSee('Bloc Urgences', false);

        $this->actingAs($admin)
            ->get(route('operating_blocks.index', ['service' => $activeService->id]))
            ->assertOk()
            ->assertSee('Bloc Urgences', false)
            ->assertDontSee('Bloc Réanimation', false);
    }

    public function test_ajax_panel_returns_table_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Endoscopie', 'code' => 'ENDO']);
        OperatingBlock::factory()->for($service)->create(['name' => 'Bloc Endoscopie', 'code' => 'BLOC-07']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-OperatingBlock-Panel' => 'table',
            ])
            ->get(route('operating_blocks.index', ['search' => 'Endo']))
            ->assertOk()
            ->assertSee('Bloc Endoscopie', false)
            ->assertDontSee('Structure &amp; Organisation', false);
    }

    public function test_code_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        OperatingBlock::factory()->for($service)->create(['code' => 'BLOC-DUP']);

        $this->actingAs($admin)
            ->post(route('operating_blocks.store'), [
                'service_id' => $service->id,
                'name' => 'Doublon',
                'code' => 'BLOC-DUP',
                'location' => 'Niveau 0',
            ])
            ->assertSessionHasErrors('code');
    }
}
