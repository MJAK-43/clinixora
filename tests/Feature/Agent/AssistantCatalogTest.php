<?php

namespace Tests\Feature\Agent;

use App\Models\User;
use Database\Seeders\AgentActionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssistantCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(AgentActionSeeder::class);
    }

    public function test_admin_receives_geography_module_with_actions_and_syntax(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->getJson(route('agent.catalog'))
            ->assertOk()
            ->assertJsonStructure([
                'modules' => [
                    [
                        'key',
                        'label',
                        'description',
                        'actions' => [
                            ['action_key', 'label', 'syntax', 'requires_confirmation'],
                        ],
                    ],
                ],
            ]);

        $modules = $response->json('modules');
        $this->assertCount(3, $modules);

        $geography = collect($modules)->firstWhere('key', 'geography');
        $this->assertNotNull($geography);
        $this->assertSame('Géographie', $geography['label']);
        $this->assertCount(15, $geography['actions']);

        $specialties = collect($modules)->firstWhere('key', 'specialties');
        $this->assertNotNull($specialties);
        $this->assertSame('Spécialités', $specialties['label']);
        $this->assertCount(5, $specialties['actions']);

        $services = collect($modules)->firstWhere('key', 'services');
        $this->assertNotNull($services);
        $this->assertSame('Services', $services['label']);
        $this->assertCount(5, $services['actions']);

        $listCities = collect($geography['actions'])->firstWhere('action_key', 'geography.list_cities');
        $this->assertNotNull($listCities);
        $this->assertStringContainsString('villes', $listCities['syntax']);
    }

    public function test_secretary_receives_empty_catalog(): void
    {
        $secretary = User::factory()->secretary()->create();

        $this->actingAs($secretary)
            ->getJson(route('agent.catalog'))
            ->assertOk()
            ->assertJsonPath('modules', []);
    }
}
