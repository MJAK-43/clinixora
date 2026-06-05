<?php

namespace App\Http\Controllers\Services;

use App\Domain\Services\Actions\CreateServiceAction;
use App\Domain\Services\Actions\DeleteServiceAction;
use App\Domain\Services\Actions\UpdateServiceAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Concerns\RedirectsToServicesIndex;
use App\Http\Requests\Services\StoreServiceRequest;
use App\Http\Requests\Services\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use RedirectsToServicesIndex;

    public function store(StoreServiceRequest $request, CreateServiceAction $action): RedirectResponse
    {
        try {
            $service = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->servicesRedirect($request, error: 'Impossible de créer le service. Vérifiez le code (déjà utilisé).');
        }

        return $this->servicesRedirect(
            $request,
            success: "Le service {$service->name} a été créé."
        );
    }

    public function update(UpdateServiceRequest $request, Service $service, UpdateServiceAction $action): RedirectResponse
    {
        try {
            $service = $action->execute($service, $request->validated());
        } catch (\Throwable) {
            return $this->servicesRedirect(
                $request,
                ['edit_service' => $service->id],
                error: 'Impossible de modifier le service.'
            );
        }

        return $this->servicesRedirect(
            $request,
            success: "Le service {$service->name} a été mis à jour."
        );
    }

    public function destroy(Request $request, Service $service, DeleteServiceAction $action): RedirectResponse
    {
        $this->authorize('delete', $service);

        $name = $service->name;

        try {
            $action->execute($service);
        } catch (\Throwable) {
            return $this->servicesRedirect($request, error: 'Impossible de supprimer le service.');
        }

        return $this->servicesRedirect(
            $request,
            success: "Le service {$name} a été supprimé."
        );
    }
}
