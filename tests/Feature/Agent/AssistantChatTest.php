<?php

namespace Tests\Feature\Agent;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Database\Seeders\AgentActionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssistantChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(AgentActionSeeder::class);
    }

    public function test_admin_can_list_cities_via_assistant(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);
        City::factory()->for($country)->create(['name' => 'Yaoundé', 'code' => 'YAO']);

        $response = $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), [
                'message' => 'liste les villes du Cameroun',
            ]);

        $response->assertOk()
            ->assertJsonPath('display.items', fn ($items) => collect($items)->contains(fn ($i) => str_contains($i, 'Yaoundé')));
    }

    public function test_create_city_requires_confirmation_then_succeeds(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);

        $pending = $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), [
                'message' => 'créer la ville Bafoussam code BAF dans Cameroun',
            ])
            ->assertOk()
            ->assertJsonStructure(['pending' => ['token', 'action_key', 'summary']])
            ->json('pending');

        $this->actingAs($admin)
            ->postJson(route('agent.confirm'), ['token' => $pending['token']])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'Bafoussam'));

        $this->assertDatabaseHas('cities', [
            'country_id' => $country->id,
            'code' => 'BAF',
        ]);
    }

    public function test_secretary_cannot_execute_geography_actions(): void
    {
        $secretary = User::factory()->secretary()->create();
        Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);

        $this->actingAs($secretary)
            ->postJson(route('agent.messages.store'), [
                'message' => 'liste les villes du Cameroun',
            ])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'permission'));
    }

    public function test_admin_can_search_city_via_assistant(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);
        City::factory()->for($country)->create(['name' => 'Yaoundé', 'code' => 'YAO']);

        $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), [
                'message' => 'rechercher la ville Yaoun dans Cameroun',
            ])
            ->assertOk()
            ->assertJsonPath('display.items', fn ($items) => collect($items)->contains(fn ($i) => str_contains($i, 'Yaoundé')));
    }

    public function test_admin_can_list_countries_via_assistant(): void
    {
        $admin = User::factory()->admin()->create();
        Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);

        $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), ['message' => 'liste les pays'])
            ->assertOk()
            ->assertJsonPath('reset_explorer', true)
            ->assertJsonStructure(['display' => ['type', 'items', 'total']]);
    }

    public function test_help_message_is_returned(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), ['message' => 'aide'])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => stripos($reply, 'catalogue') !== false);
    }

    public function test_admin_can_list_specialties_via_assistant(): void
    {
        $admin = User::factory()->admin()->create();
        \App\Models\Specialty::factory()->create(['name' => 'Cardiologie', 'code' => 'CARD']);

        $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), ['message' => 'liste les spécialités'])
            ->assertOk()
            ->assertJsonPath('display.items', fn ($items) => collect($items)->contains(fn ($i) => str_contains($i, 'Cardiologie')));
    }

    public function test_create_specialty_requires_confirmation_then_succeeds(): void
    {
        $admin = User::factory()->admin()->create();

        $pending = $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), [
                'message' => 'créer la spécialité Proctologie code PROC',
            ])
            ->assertOk()
            ->assertJsonStructure(['pending' => ['token', 'action_key', 'summary']])
            ->json('pending');

        $this->actingAs($admin)
            ->postJson(route('agent.confirm'), ['token' => $pending['token']])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'Proctologie'));

        $this->assertDatabaseHas('specialties', ['code' => 'PROC']);
    }

    public function test_secretary_cannot_execute_specialty_actions(): void
    {
        $secretary = User::factory()->secretary()->create();
        \App\Models\Specialty::factory()->create(['name' => 'Cardiologie', 'code' => 'CARD']);

        $this->actingAs($secretary)
            ->postJson(route('agent.messages.store'), ['message' => 'liste les spécialités'])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'permission'));
    }

    public function test_admin_can_list_services_via_assistant(): void
    {
        $admin = User::factory()->admin()->create();
        \App\Models\Service::factory()->create(['name' => 'Urgences', 'code' => 'URG']);

        $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), ['message' => 'liste les services'])
            ->assertOk()
            ->assertJsonPath('display.items', fn ($items) => collect($items)->contains(fn ($i) => str_contains($i, 'Urgences')));
    }

    public function test_create_service_requires_confirmation_then_succeeds(): void
    {
        $admin = User::factory()->admin()->create();

        $pending = $this->actingAs($admin)
            ->postJson(route('agent.messages.store'), [
                'message' => 'créer le service Dialyse code DIAL',
            ])
            ->assertOk()
            ->assertJsonStructure(['pending' => ['token', 'action_key', 'summary']])
            ->json('pending');

        $this->actingAs($admin)
            ->postJson(route('agent.confirm'), ['token' => $pending['token']])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'Dialyse'));

        $this->assertDatabaseHas('services', ['code' => 'DIAL']);
    }

    public function test_secretary_cannot_execute_service_actions(): void
    {
        $secretary = User::factory()->secretary()->create();
        \App\Models\Service::factory()->create(['name' => 'Urgences', 'code' => 'URG']);

        $this->actingAs($secretary)
            ->postJson(route('agent.messages.store'), ['message' => 'liste les services'])
            ->assertOk()
            ->assertJsonPath('reply', fn ($reply) => str_contains($reply, 'permission'));
    }
}
