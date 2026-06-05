<?php

namespace App\Http\Controllers\ReceptionRooms;

use App\Domain\ReceptionRooms\Actions\CreateReceptionRoomAction;
use App\Domain\ReceptionRooms\Actions\DeleteReceptionRoomAction;
use App\Domain\ReceptionRooms\Actions\UpdateReceptionRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReceptionRooms\Concerns\RedirectsToReceptionRoomsIndex;
use App\Http\Requests\ReceptionRooms\StoreReceptionRoomRequest;
use App\Http\Requests\ReceptionRooms\UpdateReceptionRoomRequest;
use App\Models\ReceptionRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReceptionRoomController extends Controller
{
    use RedirectsToReceptionRoomsIndex;

    public function store(StoreReceptionRoomRequest $request, CreateReceptionRoomAction $action): RedirectResponse
    {
        try {
            $room = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->receptionRoomsRedirect($request, error: 'Impossible de créer la salle d\'accueil. Vérifiez le code (déjà utilisé).');
        }

        return $this->receptionRoomsRedirect(
            $request,
            success: "La salle {$room->name} a été créée."
        );
    }

    public function update(UpdateReceptionRoomRequest $request, ReceptionRoom $receptionRoom, UpdateReceptionRoomAction $action): RedirectResponse
    {
        try {
            $room = $action->execute($receptionRoom, $request->validated());
        } catch (\Throwable) {
            return $this->receptionRoomsRedirect(
                $request,
                ['edit_reception_room' => $receptionRoom->id],
                error: 'Impossible de modifier la salle d\'accueil.'
            );
        }

        return $this->receptionRoomsRedirect(
            $request,
            success: "La salle {$room->name} a été mise à jour."
        );
    }

    public function destroy(Request $request, ReceptionRoom $receptionRoom, DeleteReceptionRoomAction $action): RedirectResponse
    {
        $this->authorize('delete', $receptionRoom);

        $name = $receptionRoom->name;

        try {
            $action->execute($receptionRoom);
        } catch (\Throwable) {
            return $this->receptionRoomsRedirect($request, error: 'Impossible de supprimer la salle d\'accueil.');
        }

        return $this->receptionRoomsRedirect(
            $request,
            success: "La salle {$name} a été supprimée."
        );
    }
}
