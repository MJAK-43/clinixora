<?php

namespace App\Domain\Agent\Handlers\CareRooms;

use App\Domain\Agent\Concerns\AuthorizesCareRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\CareRooms\Concerns\ResolvesCareRoomEntities;
use App\Domain\CareRooms\Actions\CreateCareRoomAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateCareRoomHandler implements AgentActionHandler
{
    use AuthorizesCareRoomAgent;
    use ResolvesCareRoomEntities;

    public const ACTION_KEY = 'care_rooms.create_care_room';

    public function __construct(private readonly CreateCareRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer la salle de soin Salle Infirmière code SOIN-01 service Médecine générale localisation Niveau 1';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function describe(array $params): string
    {
        return "Créer la salle de soin « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));
        $location = trim((string) ($params['location'] ?? ''));

        if ($name === '' || $code === '' || $location === '') {
            throw ValidationException::withMessages(['name' => 'Nom, code et localisation requis.']);
        }

        return [
            'service_id' => $this->resolveServiceId($params),
            'name' => $name,
            'code' => $code,
            'location' => $location,
            'is_active' => $params['is_active'] ?? true,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        try {
            $room = $this->action->execute($params);
            $room->load('service');
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer la salle (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("La salle de soin « {$room->name} » ({$room->code}) a été créée pour {$room->service?->name}.");
    }
}
