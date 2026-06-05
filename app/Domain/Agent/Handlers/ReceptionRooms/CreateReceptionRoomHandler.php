<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms;

use App\Domain\Agent\Concerns\AuthorizesReceptionRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\ReceptionRooms\Actions\CreateReceptionRoomAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateReceptionRoomHandler implements AgentActionHandler
{
    use AuthorizesReceptionRoomAgent;

    public const ACTION_KEY = 'reception_rooms.create_reception_room';

    public function __construct(private readonly CreateReceptionRoomAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer la salle d\'accueil Accueil Principal code ACC-01 localisation Hall';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function describe(array $params): string
    {
        return "Créer la salle d'accueil « {$params['name']} » ({$params['code']})";
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
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer la salle (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("La salle d'accueil « {$room->name} » ({$room->code}) a été créée à {$room->location}.");
    }
}
