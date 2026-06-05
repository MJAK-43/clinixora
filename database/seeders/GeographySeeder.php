<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeographySeeder extends Seeder
{
    /**
     * Cameroun + villes (>10 000 hab., Wikipedia) + 5 quartiers par ville.
     */
    public function run(): void
    {
        $country = Country::query()->updateOrCreate(
            ['code' => 'CM'],
            [
                'name' => 'Cameroun',
                'flag_code' => 'cm',
                'is_active' => true,
            ]
        );

        $districtTemplates = [
            ['name' => 'Centre-ville', 'code' => 'CTR'],
            ['name' => 'Quartier Nord', 'code' => 'NRD'],
            ['name' => 'Quartier Sud', 'code' => 'SUD'],
            ['name' => 'Quartier Est', 'code' => 'EST'],
            ['name' => 'Quartier Ouest', 'code' => 'OUD'],
        ];

        $cityNames = require database_path('data/cameroon_cities.php');
        $usedCityCodes = [];

        foreach ($cityNames as $cityName) {
            $code = $this->uniqueCityCode($cityName, $usedCityCodes);
            $usedCityCodes[] = $code;

            $city = City::query()->updateOrCreate(
                [
                    'country_id' => $country->id,
                    'code' => $code,
                ],
                [
                    'name' => $cityName,
                    'is_active' => true,
                ]
            );

            foreach ($districtTemplates as $template) {
                District::query()->updateOrCreate(
                    [
                        'city_id' => $city->id,
                        'code' => $template['code'],
                    ],
                    [
                        'name' => $template['name'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    /**
     * @param  list<string>  $usedCodes
     */
    private function uniqueCityCode(string $cityName, array &$usedCodes): string
    {
        $ascii = Str::ascii($cityName);
        $letters = preg_replace('/[^A-Za-z]/', '', $ascii) ?? '';
        $base = strtoupper(substr($letters, 0, 4));

        if ($base === '') {
            $base = 'VIL';
        }

        $code = $base;
        $suffix = 1;

        while (in_array($code, $usedCodes, true)) {
            $suffix++;
            $code = substr($base, 0, 3).$suffix;
        }

        return $code;
    }
}
