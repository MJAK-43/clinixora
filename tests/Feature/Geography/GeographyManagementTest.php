<?php

namespace Tests\Feature\Geography;

use App\Domain\Geography\Services\GeographyQueryService;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeographyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_ajax_panel_returns_html_without_full_page(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);

        $this->actingAs($admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'X-Geography-Panel' => 'countries',
            ])
            ->get(route('geography.countries.index', ['country_search' => 'Cam']))
            ->assertOk()
            ->assertSee('Cameroun', false)
            ->assertDontSee('geo-countries-body', false);
    }

    public function test_live_search_filters_countries_cities_and_districts(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create(['name' => 'Cameroun', 'code' => 'CM']);
        Country::factory()->create(['name' => 'France', 'code' => 'FR']);
        $city = City::factory()->for($country)->create(['name' => 'Yaoundé', 'code' => 'YAO']);
        City::factory()->for($country)->create(['name' => 'Douala', 'code' => 'DLA']);
        District::factory()->for($city)->create(['name' => 'Quartier TKC', 'code' => 'TKC']);
        District::factory()->for($city)->create(['name' => 'Centre-ville', 'code' => 'CTR']);

        $this->actingAs($admin)
            ->get(route('geography.countries.index', ['country_search' => 'Cam']))
            ->assertOk()
            ->assertSee('Cameroun', false)
            ->assertDontSee('France', false);

        $this->actingAs($admin)
            ->get(route('geography.countries.index', [
                'country' => $country->id,
                'city_search' => 'Yaoun',
            ]))
            ->assertOk()
            ->assertSee('Yaoundé', false)
            ->assertDontSee('Douala', false);

        $this->actingAs($admin)
            ->get(route('geography.countries.index', [
                'country' => $country->id,
                'city' => $city->id,
                'district_search' => 'TKC',
            ]))
            ->assertOk()
            ->assertSee('Quartier TKC', false)
            ->assertDontSee('Centre-ville', false);
    }

    public function test_geography_page_is_displayed_for_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('geography.countries.index'));

        $response->assertOk();
        $response->assertSee('Pays', false);
        $response->assertSee('Villes du pays', false);
        $response->assertSee('Quartiers de la ville', false);
    }

    public function test_secretary_cannot_access_geography_page(): void
    {
        $user = User::factory()->secretary()->create();

        $this->actingAs($user)
            ->get(route('geography.countries.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_country(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('geography.countries.store'), [
                'name' => 'France',
                'code' => 'FR',
                'is_active' => 1,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $country = Country::query()->where('code', 'FR')->first();
        $this->assertNotNull($country);

        $this->actingAs($admin)
            ->patch(route('geography.countries.update', $country), [
                'name' => 'République française',
                'code' => 'FR',
                'is_active' => 1,
                'country' => $country->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('République française', $country->fresh()->name);

        $this->actingAs($admin)
            ->delete(route('geography.countries.destroy', $country).'?country='.$country->id)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
    }

    public function test_admin_can_manage_city_linked_to_country(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create();

        $this->actingAs($admin)
            ->post(route('geography.cities.store', $country), [
                'name' => 'Lyon',
                'code' => 'LYO',
                'is_active' => 1,
                'country' => $country->id,
            ])
            ->assertSessionHas('success');

        $city = City::query()->where('country_id', $country->id)->where('code', 'LYO')->first();
        $this->assertNotNull($city);

        $this->actingAs($admin)
            ->post(route('geography.cities.store', $country), [
                'name' => 'Lyon bis',
                'code' => 'LYO',
                'is_active' => 1,
                'country' => $country->id,
            ])
            ->assertSessionHasErrors('code');

        $this->actingAs($admin)
            ->patch(route('geography.cities.update', $city), [
                'name' => 'Lyon Métropole',
                'code' => 'LYO',
                'is_active' => 1,
                'country' => $country->id,
                'city' => $city->id,
            ])
            ->assertSessionHas('success');

        $this->assertSame('Lyon Métropole', $city->fresh()->name);
    }

    public function test_admin_can_manage_district_linked_to_city(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create();
        $city = City::factory()->for($country)->create();

        $this->actingAs($admin)
            ->post(route('geography.districts.store', $city), [
                'name' => 'Centre-ville',
                'code' => 'CTR',
                'is_active' => 1,
                'country' => $country->id,
                'city' => $city->id,
            ])
            ->assertSessionHas('success');

        $district = District::query()->where('city_id', $city->id)->where('code', 'CTR')->first();
        $this->assertNotNull($district);

        $this->actingAs($admin)
            ->delete(route('geography.districts.destroy', $district).'?country='.$country->id.'&city='.$city->id)
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('districts', ['id' => $district->id]);
    }

    public function test_selected_city_redirects_to_correct_cities_page(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create();
        foreach (range(1, 16) as $i) {
            City::factory()->for($country)->create([
                'name' => sprintf('Ville %02d', $i),
                'code' => sprintf('V%02d', $i),
            ]);
        }
        $yaounde = City::factory()->for($country)->create(['name' => 'Yaoundé', 'code' => 'YAO']);

        $this->actingAs($admin)
            ->get(route('geography.countries.index', ['country' => $country->id, 'city' => $yaounde->id]))
            ->assertRedirect(route('geography.countries.index', [
                'country' => $country->id,
                'city' => $yaounde->id,
                'cities_page' => 2,
            ]));
    }

    public function test_page_for_city_returns_expected_page(): void
    {
        $country = Country::factory()->create();
        foreach (range(1, 16) as $i) {
            City::factory()->for($country)->create(['name' => sprintf('Ville %02d', $i)]);
        }
        $yaounde = City::factory()->for($country)->create(['name' => 'Yaoundé', 'code' => 'YAO']);

        $page = app(GeographyQueryService::class)->pageForCity($country, $yaounde);
        $this->assertSame(2, $page);
    }

    public function test_country_with_cities_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create();
        City::factory()->for($country)->create();

        $this->actingAs($admin)
            ->delete(route('geography.countries.destroy', $country).'?country='.$country->id)
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('countries', ['id' => $country->id]);
    }

    public function test_admin_can_delete_city_with_districts(): void
    {
        $admin = User::factory()->admin()->create();
        $country = Country::factory()->create();
        $city = City::factory()->for($country)->create();
        $district = District::factory()->for($city)->create();

        $this->actingAs($admin)
            ->delete(route('geography.cities.destroy', $city), [
                '_redirect' => [
                    'country' => $country->id,
                    'cities_page' => 1,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('cities', ['id' => $city->id]);
        $this->assertDatabaseMissing('districts', ['id' => $district->id]);
    }
}
