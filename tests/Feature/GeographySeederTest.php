<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Database\Seeders\GeographySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeographySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_geography_seeder_creates_cameroon_cities_and_five_districts_each(): void
    {
        $this->seed(GeographySeeder::class);

        $country = Country::query()->where('code', 'CM')->first();
        $this->assertNotNull($country);
        $this->assertSame('Cameroun', $country->name);

        $cityCount = City::query()->where('country_id', $country->id)->count();
        $this->assertSame(76, $cityCount);

        $districtCount = District::query()
            ->whereIn('city_id', City::query()->where('country_id', $country->id)->pluck('id'))
            ->count();

        $this->assertSame(76 * 5, $districtCount);

        $yaounde = City::query()->where('country_id', $country->id)->where('name', 'Yaoundé')->first();
        $this->assertNotNull($yaounde);
        $this->assertSame(5, $yaounde->districts()->count());
    }
}
