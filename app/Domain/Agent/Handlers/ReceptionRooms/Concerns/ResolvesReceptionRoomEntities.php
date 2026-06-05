<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms\Concerns;

use App\Domain\ReceptionRooms\Services\ReceptionRoomQueryService;
use App\Models\ReceptionRoom;
use Illuminate\Validation\ValidationException;

trait ResolvesReceptionRoomEntities
{
    protected function resolveReceptionRoom(array $params): ReceptionRoom
    {
        if (isset($params['reception_room_id'])) {
            $room = ReceptionRoom::query()->find($params['reception_room_id']);
            if ($room) {
                return $room;
            }
        }

        $needle = trim((string) ($params['reception_room'] ?? $params['room'] ?? $params['name'] ?? $params['code'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['reception_room' => 'Indiquez la salle (nom ou code).']);
        }

        $room = app(ReceptionRoomQueryService::class)->findByNameOrCode($needle);

        if ($room === null) {
            throw ValidationException::withMessages(['reception_room' => "Salle d'accueil introuvable : « {$needle} »."]);
        }

        return $room;
    }
}
