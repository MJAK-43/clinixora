<?php

namespace App\Http\Controllers\CareRooms;

use App\Domain\CareRooms\Actions\CreateCareRoomAction;
use App\Domain\CareRooms\Actions\DeleteCareRoomAction;
use App\Domain\CareRooms\Actions\UpdateCareRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CareRooms\Concerns\RedirectsToCareRoomsIndex;
use App\Http\Requests\CareRooms\StoreCareRoomRequest;
use App\Http\Requests\CareRooms\UpdateCareRoomRequest;
use App\Models\CareRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CareRoomController extends Controller
{
    use RedirectsToCareRoomsIndex;

    public function store(StoreCareRoomRequest $request, CreateCareRoomAction $action): RedirectResponse
    {
        try {
            $room = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->careRoomsRedirect($request, error: 'Impossible de créer la salle de soin. Vérifiez le code (déjà utilisé).');
        }

        return $this->careRoomsRedirect(
            $request,
            success: "La salle {$room->name} a été créée."
        );
    }

    public function update(UpdateCareRoomRequest $request, CareRoom $careRoom, UpdateCareRoomAction $action): RedirectResponse
    {
        try {
            $room = $action->execute($careRoom, $request->validated());
        } catch (\Throwable) {
            return $this->careRoomsRedirect(
                $request,
                ['edit_care_room' => $careRoom->id],
                error: 'Impossible de modifier la salle de soin.'
            );
        }

        return $this->careRoomsRedirect(
            $request,
            success: "La salle {$room->name} a été mise à jour."
        );
    }

    public function destroy(Request $request, CareRoom $careRoom, DeleteCareRoomAction $action): RedirectResponse
    {
        $this->authorize('delete', $careRoom);

        $name = $careRoom->name;

        try {
            $action->execute($careRoom);
        } catch (\Throwable) {
            return $this->careRoomsRedirect($request, error: 'Impossible de supprimer la salle de soin.');
        }

        return $this->careRoomsRedirect(
            $request,
            success: "La salle {$name} a été supprimée."
        );
    }
}
