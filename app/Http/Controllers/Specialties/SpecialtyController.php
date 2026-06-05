<?php

namespace App\Http\Controllers\Specialties;

use App\Domain\Specialties\Actions\CreateSpecialtyAction;
use App\Domain\Specialties\Actions\DeleteSpecialtyAction;
use App\Domain\Specialties\Actions\UpdateSpecialtyAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Specialties\Concerns\RedirectsToSpecialtiesIndex;
use App\Http\Requests\Specialties\StoreSpecialtyRequest;
use App\Http\Requests\Specialties\UpdateSpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    use RedirectsToSpecialtiesIndex;

    public function store(StoreSpecialtyRequest $request, CreateSpecialtyAction $action): RedirectResponse
    {
        try {
            $specialty = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->specialtiesRedirect($request, error: 'Impossible de créer la spécialité. Vérifiez le code (déjà utilisé).');
        }

        return $this->specialtiesRedirect(
            $request,
            success: "La spécialité {$specialty->name} a été créée."
        );
    }

    public function update(UpdateSpecialtyRequest $request, Specialty $specialty, UpdateSpecialtyAction $action): RedirectResponse
    {
        try {
            $specialty = $action->execute($specialty, $request->validated());
        } catch (\Throwable) {
            return $this->specialtiesRedirect(
                $request,
                ['edit_specialty' => $specialty->id],
                error: 'Impossible de modifier la spécialité.'
            );
        }

        return $this->specialtiesRedirect(
            $request,
            success: "La spécialité {$specialty->name} a été mise à jour."
        );
    }

    public function destroy(Request $request, Specialty $specialty, DeleteSpecialtyAction $action): RedirectResponse
    {
        $this->authorize('delete', $specialty);

        $name = $specialty->name;

        try {
            $action->execute($specialty);
        } catch (\Throwable) {
            return $this->specialtiesRedirect($request, error: 'Impossible de supprimer la spécialité.');
        }

        return $this->specialtiesRedirect(
            $request,
            success: "La spécialité {$name} a été supprimée."
        );
    }
}
