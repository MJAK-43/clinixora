<?php

namespace App\Domain\Geography\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class GeographyQueryService
{
    public const COUNTRIES_PER_PAGE = 8;

    public const CITIES_PER_PAGE = 15;

    public const DISTRICTS_PER_PAGE = 15;

    public function emptyPaginator(string $pageName, int $perPage = self::CITIES_PER_PAGE): LengthAwarePaginator
    {
        return (new Paginator([], 0, $perPage, 1, [
            'path' => request()->url(),
            'pageName' => $pageName,
        ]))->withQueryString();
    }

    public function paginateCountries(?string $search = null, int $perPage = self::COUNTRIES_PER_PAGE): LengthAwarePaginator
    {
        return Country::query()
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'countries_page')
            ->withQueryString();
    }

    public function paginateCities(Country $country, ?string $search = null, int $perPage = self::CITIES_PER_PAGE): LengthAwarePaginator
    {
        return $this->citiesQuery($country, $search)
            ->paginate($perPage, ['*'], 'cities_page')
            ->withQueryString();
    }

    public function paginateDistricts(City $city, ?string $search = null, int $perPage = self::DISTRICTS_PER_PAGE): LengthAwarePaginator
    {
        return $this->districtsQuery($city, $search)
            ->paginate($perPage, ['*'], 'districts_page')
            ->withQueryString();
    }

    public function pageForCity(Country $country, City $city, ?string $search = null, int $perPage = self::CITIES_PER_PAGE): int
    {
        $position = $this->citiesQuery($country, $search)
            ->where(function ($query) use ($city) {
                $query->where('name', '<', $city->name)
                    ->orWhere(function ($inner) use ($city) {
                        $inner->where('name', $city->name)->where('id', '<=', $city->id);
                    });
            })
            ->count();

        return max(1, (int) ceil($position / $perPage));
    }

    public function pageForDistrict(City $city, District $district, ?string $search = null, int $perPage = self::DISTRICTS_PER_PAGE): int
    {
        $position = $this->districtsQuery($city, $search)
            ->where(function ($query) use ($district) {
                $query->where('name', '<', $district->name)
                    ->orWhere(function ($inner) use ($district) {
                        $inner->where('name', $district->name)->where('id', '<=', $district->id);
                    });
            })
            ->count();

        return max(1, (int) ceil($position / $perPage));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<City>
     */
    private function citiesQuery(Country $country, ?string $search)
    {
        return City::query()
            ->where('country_id', $country->id)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->orderBy('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<District>
     */
    private function districtsQuery(City $city, ?string $search)
    {
        return District::query()
            ->where('city_id', $city->id)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->orderBy('id');
    }
}
