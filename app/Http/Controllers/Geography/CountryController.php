<?php

namespace App\Http\Controllers\Geography;

use App\Domain\Geography\Actions\CreateCountryAction;
use App\Domain\Geography\Actions\DeleteCountryAction;
use App\Domain\Geography\Actions\UpdateCountryAction;
use App\Domain\Geography\Exceptions\GeographyConflictException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Geography\Concerns\RedirectsToGeographyIndex;
use App\Http\Requests\Geography\StoreCountryRequest;
use App\Http\Requests\Geography\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use RedirectsToGeographyIndex;

    public function store(StoreCountryRequest $request, CreateCountryAction $action): RedirectResponse
    {
        try {
            $country = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect($request, error: 'Impossible de créer le pays. Vérifiez le code (déjà utilisé).');
        }

        return $this->geographyRedirect(
            $request,
            ['country' => $country->id, 'city' => null],
            success: "Le pays {$country->name} a été créé."
        );
    }

    public function update(UpdateCountryRequest $request, Country $country, UpdateCountryAction $action): RedirectResponse
    {
        try {
            $country = $action->execute($country, $request->validated());
        } catch (\Throwable) {
            return $this->geographyRedirect(
                $request,
                ['edit_country' => $country->id],
                error: 'Impossible de modifier le pays.'
            );
        }

        return $this->geographyRedirect(
            $request,
            ['country' => $country->id],
            success: "Le pays {$country->name} a été mis à jour."
        );
    }

    public function destroy(Request $request, Country $country, DeleteCountryAction $action): RedirectResponse
    {
        $this->authorize('delete', $country);

        $name = $country->name;

        try {
            $action->execute($country);
        } catch (GeographyConflictException $e) {
            return $this->geographyRedirect($request, ['country' => $country->id], error: $e->getMessage());
        } catch (\Throwable) {
            return $this->geographyRedirect($request, ['country' => $country->id], error: 'Impossible de supprimer le pays.');
        }

        return $this->geographyRedirect(
            $request,
            ['country' => null, 'city' => null],
            success: "Le pays {$name} a été supprimé."
        );
    }
}
