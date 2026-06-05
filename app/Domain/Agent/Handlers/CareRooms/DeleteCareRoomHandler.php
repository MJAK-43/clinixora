<?php

namespace App\Domain\Agent\Handlers\CareRooms;

use App\Domain\Agent\Concerns\AuthorizesCareRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\CareRooms\Concerns\ResolvesCareRoomEntities;
use App\Domain\CareRooms\Actions\DeleteCareRoomAction;
use App\Models\User;

class DeleteCareRoomHandler implements AgentActionHandler
{
    use AuthorizesCareRoomAgent;
    use ResolvesCareRoomEntities;

    public const ACTION_KEY = 'care_rooms.delete_care_room';

    public function __construct(private readonly DeleteCareRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer la salle de soin Salle Infirmière';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer la salle de soin « {$params['care_room']} »";
    }

    public function validateParams(array $params): array
    {
        $room = $this->resolveCareRoom($params);

        return ['care_room_id' => $room->id, 'care_room' => $room->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $room = $this->resolveCareRoom(['care_room_id' => $params['care_room_id']]);
        $this->action->execute($room);

        return new AgentActionResult("La salle de soin « {$room->name} » a été supprimée.");
    }
}
