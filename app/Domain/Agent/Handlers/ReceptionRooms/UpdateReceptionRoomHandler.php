<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms;

use App\Domain\Agent\Concerns\AuthorizesReceptionRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\ReceptionRooms\Concerns\ResolvesReceptionRoomEntities;
use App\Domain\ReceptionRooms\Actions\UpdateReceptionRoomAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateReceptionRoomHandler implements AgentActionHandler
{
    use AuthorizesReceptionRoomAgent;
    use ResolvesReceptionRoomEntities;

    public const ACTION_KEY = 'reception_rooms.update_reception_room';

    public function __construct(private readonly UpdateReceptionRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier la salle d\'accueil Accueil Principal en Accueil Principal code ACC-01 localisation Hall';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function describe(array $params): string
    {
        return "Modifier la salle d'accueil « {$params['reception_room']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $room = $this->resolveReceptionRoom($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));
        $location = trim((string) ($params['location'] ?? $room->location));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'reception_room_id' => $room->id,
            'reception_room' => $room->name,
            'name' => $name,
            'code' => $code,
            'location' => $location,
            'is_active' => $params['is_active'] ?? $room->is_active,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $room = $this->resolveReceptionRoom(['reception_room_id' => $params['reception_room_id']]);
        $updated = $this->action->execute($room, $params);

        return new AgentActionResult("La salle d'accueil a été mise à jour : « {$updated->name} » ({$updated->code}).");
    }
}
