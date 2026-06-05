<?php

namespace App\Http\Controllers\Geography;

use App\Domain\Geography\Actions\CreateCityAction;
use App\Domain\Geography\Actions\DeleteCityAction;
use App\Domain\Geography\Actions\UpdateCityAction;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Domain\Geography\Exceptions\GeographyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Geography\Concerns\RedirectsToGeographyIndex;
use App\Http\Requests\Geography\StoreCityRequest;
use App\Http\Requests\Geography\UpdateCityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use RedirectsToGeographyIndex;

    public function store(StoreCityRequest $request, Country $country, CreateCityAction $action, GeographyQueryService $queries): RedirectResponse
    {
        try {
            $city = $action->execute($country, $request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['country' => $country->id, 'create_city' => 1],
                error: 'Impossible de créer la ville. Le code existe déjà pour ce pays.'
            );
        }

        return $this->geographyRedirect(
            $request,
            [
                'country' => $country->id,
                'city' => $city->id,
                'cities_page' => $queries->pageForCity($country, $city),
            ],
            success: "La ville {$city->name} a été ajoutée à {$country->name}."
        );
    }

    public function update(UpdateCityRequest $request, City $city, UpdateCityAction $action): RedirectResponse
    {
        try {
            $city = $action->execute($city, $request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['country' => $city->country_id, 'city' => $city->id, 'edit_city' => $city->id],
                error: 'Impossible de modifier la ville.'
            );
        }

        return $this->geographyRedirect(
            $request,
            [
                'country' => $city->country_id,
                'city' => $city->id,
                'cities_page' => app(GeographyQueryService::class)->pageForCity(
                    Country::query()->findOrFail($city->country_id),
                    $city->fresh()
                ),
            ],
            success: "La ville {$city->name} a été mise à jour."
        );
    }

    public function destroy(Request $request, City $city, DeleteCityAction $action): RedirectResponse
    {
        $this->authorize('delete', $city);

        $name = $city->name;
        $countryId = $city->country_id;

        try {
            $action->execute($city);
        } catch (GeographyConflictException $e) {
            return $this->geographyRedirect(
                $request,
                ['country' => $countryId, 'city' => $city->id],
                error: $e->getMessage()
            );
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['country' => $countryId, 'city' => $city->id],
                error: 'Impossible de supprimer la ville.'
            );
        }

        return $this->geographyRedirect(
            $request,
            ['country' => $countryId, 'city' => null],
            success: "La ville {$name} a été supprimée."
        );
    }
}
