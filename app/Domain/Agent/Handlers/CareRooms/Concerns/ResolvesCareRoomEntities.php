<?php

namespace App\Domain\Agent\Handlers\CareRooms\Concerns;

use App\Domain\CareRooms\Services\CareRoomQueryService;
use App\Domain\Services\Services\ServiceQueryService;
use App\Models\CareRoom;
use Illuminate\Validation\ValidationException;

trait ResolvesCareRoomEntities
{
    protected function resolveCareRoom(array $params): CareRoom
    {
        if (isset($params['care_room_id'])) {
            $room = CareRoom::query()->with('service')->find($params['care_room_id']);
            if ($room) {
                return $room;
            }
        }

        $needle = trim((string) ($params['care_room'] ?? $params['room'] ?? $params['name'] ?? $params['code'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['care_room' => 'Indiquez la salle (nom ou code).']);
        }

        $room = app(CareRoomQueryService::class)->findByNameOrCode($needle);

        if ($room === null) {
            throw ValidationException::withMessages(['care_room' => "Salle de soin introuvable : « {$needle} »."]);
        }

        return $room;
    }

    protected function resolveServiceId(array $params): int
    {
        if (isset($params['service_id'])) {
            return (int) $params['service_id'];
        }

        $needle = trim((string) ($params['service'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['service' => 'Indiquez le service associé.']);
        }

        $service = app(ServiceQueryService::class)->findByNameOrCode($needle);

        if ($service === null) {
            throw ValidationException::withMessages(['service' => "Service introuvable : « {$needle} »."]);
        }

        return $service->id;
    }
}
