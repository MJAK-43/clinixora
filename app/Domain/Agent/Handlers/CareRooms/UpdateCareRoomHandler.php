<?php

namespace App\Domain\Agent\Handlers\CareRooms;

use App\Domain\Agent\Concerns\AuthorizesCareRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\CareRooms\Concerns\ResolvesCareRoomEntities;
use App\Domain\CareRooms\Actions\UpdateCareRoomAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateCareRoomHandler implements AgentActionHandler
{
    use AuthorizesCareRoomAgent;
    use ResolvesCareRoomEntities;

    public const ACTION_KEY = 'care_rooms.update_care_room';

    public function __construct(private readonly UpdateCareRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier la salle de soin Salle Infirmière en Salle Infirmière code SOIN-01 service Médecine générale localisation Niveau 1';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function describe(array $params): string
    {
        return "Modifier la salle de soin « {$params['care_room']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $room = $this->resolveCareRoom($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));
        $location = trim((string) ($params['location'] ?? $room->location));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'care_room_id' => $room->id,
            'care_room' => $room->name,
            'service_id' => $this->resolveServiceId($params),
            'name' => $name,
            'code' => $code,
            'location' => $location,
            'is_active' => $params['is_active'] ?? $room->is_active,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $room = $this->resolveCareRoom(['care_room_id' => $params['care_room_id']]);
        $updated = $this->action->execute($room, $params);

        return new AgentActionResult("La salle de soin a été mise à jour : « {$updated->name} » ({$updated->code}).");
    }
}
