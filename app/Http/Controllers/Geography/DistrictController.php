<?php

namespace App\Http\Controllers\Geography;

use App\Domain\Geography\Actions\CreateDistrictAction;
use App\Domain\Geography\Actions\DeleteDistrictAction;
use App\Domain\Geography\Actions\UpdateDistrictAction;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Domain\Geography\Exceptions\GeographyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Geography\Concerns\RedirectsToGeographyIndex;
use App\Http\Requests\Geography\StoreDistrictRequest;
use App\Http\Requests\Geography\UpdateDistrictRequest;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    use RedirectsToGeographyIndex;

    public function store(StoreDistrictRequest $request, City $city, CreateDistrictAction $action, GeographyQueryService $queries): RedirectResponse
    {
        try {
            $district = $action->execute($city, $request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['country' => $city->country_id, 'city' => $city->id, 'create_district' => 1],
                error: 'Impossible de créer le quartier.'
            );
        }

        return $this->geographyRedirect(
            $request,
            [
                'country' => $city->country_id,
                'city' => $city->id,
                'cities_page' => $queries->pageForCity($city->country, $city),
                'districts_page' => $queries->pageForDistrict($city, $district),
            ],
            success: "Le quartier {$district->name} a été ajouté à {$city->name}."
        );
    }

    public function update(UpdateDistrictRequest $request, District $district, UpdateDistrictAction $action): RedirectResponse
    {
        $city = $district->city;

        try {
            $district = $action->execute($district, $request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                [
                    'country' => $city->country_id,
                    'city' => $city->id,
                    'edit_district' => $district->id,
                ],
                error: 'Impossible de modifier le quartier.'
            );
        }

        return $this->geographyRedirect(
            $request,
            ['country' => $city->country_id, 'city' => $city->id],
            success: "Le quartier {$district->name} a été mis à jour."
        );
    }

    public function destroy(Request $request, District $district, DeleteDistrictAction $action): RedirectResponse
    {
        $this->authorize('delete', $district);

        $name = $district->name;
        $city = $district->city;

        try {
            $action->execute($district);
        } catch (GeographyConflictException $e) {
            return $this->geographyRedirect(
                $request,
                ['country' => $city->country_id, 'city' => $city->id],
                error: $e->getMessage()
            );
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['country' => $city->country_id, 'city' => $city->id],
                error: 'Impossible de supprimer le quartier.'
            );
        }

        return $this->geographyRedirect(
            $request,
            ['country' => $city->country_id, 'city' => $city->id],
            success: "Le quartier {$name} a été supprimé."
        );
    }
}
