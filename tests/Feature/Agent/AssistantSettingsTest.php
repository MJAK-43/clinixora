<?php

namespace Tests\Feature\Agent;

use App\Models\AssistantSetting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssistantSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_can_view_and_update_assistant_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('parametres.assistant'))
            ->assertOk()
            ->assertSee('Assistant Clinixora', false);

        $this->actingAs($admin)
            ->patch(route('parametres.assistant.update'), [
                'mode' => 'mvp',
                'assistant_enabled' => '1',
                'require_confirmation_writes' => '1',
            ])
            ->assertRedirect(route('parametres.assistant'));

        $this->assertSame('mvp', AssistantSetting::current()->mode);
    }

    public function test_secretary_cannot_access_assistant_settings(): void
    {
        $secretary = User::factory()->secretary()->create();

        $this->actingAs($secretary)
            ->get(route('parametres.assistant'))
            ->assertForbidden();
    }
}
