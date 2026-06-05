<?php

namespace App\Domain\Agent\Handlers\Geography\Concerns;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Illuminate\Validation\ValidationException;

trait ResolvesGeographyEntities
{
    /**
     * @param  array<string, mixed>  $params
     */
    protected function resolveCountry(array $params, bool $required = true, string $default = 'Cameroun'): ?Country
    {
        if (! empty($params['country_id'])) {
            return Country::query()->findOrFail((int) $params['country_id']);
        }

        $name = trim((string) ($params['country'] ?? $params['country_name'] ?? ($required ? '' : $default)));

        if ($name === '') {
            if (! $required) {
                return null;
            }

            throw ValidationException::withMessages([
                'country' => 'Indiquez le pays (ex. Cameroun).',
            ]);
        }

        $country = Country::query()
            ->where(function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                    ->orWhere('code', 'like', '%'.strtoupper($name).'%');
            })
            ->orderBy('name')
            ->first();

        if ($country === null) {
            throw ValidationException::withMessages([
                'country' => "Pays « {$name} » introuvable.",
            ]);
        }

        return $country;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function resolveCity(array $params, ?Country $country = null): City
    {
        if (! empty($params['city_id'])) {
            return City::query()->findOrFail((int) $params['city_id']);
        }

        $country ??= $this->resolveCountry($params);
        $name = trim((string) ($params['city'] ?? $params['city_name'] ?? $params['name'] ?? ''));

        if ($name === '') {
            throw ValidationException::withMessages([
                'city' => 'Indiquez la ville (ex. Yaoundé).',
            ]);
        }

        $city = City::query()
            ->where('country_id', $country->id)
            ->where(function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                    ->orWhere('code', 'like', '%'.strtoupper($name).'%');
            })
            ->orderBy('name')
            ->first();

        if ($city === null) {
            throw ValidationException::withMessages([
                'city' => "Ville « {$name} » introuvable dans {$country->name}.",
            ]);
        }

        return $city;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function resolveDistrict(array $params, ?City $city = null): District
    {
        if (! empty($params['district_id'])) {
            return District::query()->findOrFail((int) $params['district_id']);
        }

        $city ??= $this->resolveCity($params);
        $name = trim((string) ($params['district'] ?? $params['district_name'] ?? $params['name'] ?? ''));

        if ($name === '') {
            throw ValidationException::withMessages([
                'district' => 'Indiquez le quartier.',
            ]);
        }

        $district = District::query()
            ->where('city_id', $city->id)
            ->where(function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                    ->orWhere('code', 'like', '%'.strtoupper($name).'%');
            })
            ->orderBy('name')
            ->first();

        if ($district === null) {
            throw ValidationException::withMessages([
                'district' => "Quartier « {$name} » introuvable à {$city->name}.",
            ]);
        }

        return $district;
    }
}
