<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms;

use App\Domain\Agent\Concerns\AuthorizesReceptionRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\ReceptionRooms\Concerns\ResolvesReceptionRoomEntities;
use App\Domain\ReceptionRooms\Actions\DeleteReceptionRoomAction;
use App\Models\User;

class DeleteReceptionRoomHandler implements AgentActionHandler
{
    use AuthorizesReceptionRoomAgent;
    use ResolvesReceptionRoomEntities;

    public const ACTION_KEY = 'reception_rooms.delete_reception_room';

    public function __construct(private readonly DeleteReceptionRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer la salle d\'accueil Accueil Principal';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer la salle d'accueil « {$params['reception_room']} »";
    }

    public function validateParams(array $params): array
    {
        $room = $this->resolveReceptionRoom($params);

        return ['reception_room_id' => $room->id, 'reception_room' => $room->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $room = $this->resolveReceptionRoom(['reception_room_id' => $params['reception_room_id']]);
        $this->action->execute($room);

        return new AgentActionResult("La salle d'accueil « {$room->name} » a été supprimée.");
    }
}
