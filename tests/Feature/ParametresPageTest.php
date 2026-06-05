<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParametresPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_parametres_page_is_displayed_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('parametres'));

        $response->assertOk();
        $response->assertSee('Paramètres', false);
        $response->assertSee('Accès rapide', false);
        $response->assertSee('Paramètres par catégorie', false);
    }

    public function test_parametres_page_redirects_guests(): void
    {
        $response = $this->get(route('parametres'));

        $response->assertRedirect();
    }
}
