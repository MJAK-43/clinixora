<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms;

use App\Domain\Agent\Concerns\AuthorizesReceptionRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\ReceptionRooms\Services\ReceptionRoomQueryService;
use App\Models\User;

class ListReceptionRoomsHandler implements AgentActionHandler
{
    use AuthorizesReceptionRoomAgent;

    public const ACTION_KEY = 'reception_rooms.list_reception_rooms';

    public function __construct(private readonly ReceptionRoomQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les salles d\'accueil';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les salles d\'accueil';
    }

    public function validateParams(array $params): array
    {
        return [
            'search' => isset($params['search']) ? trim((string) $params['search']) : null,
            'status' => isset($params['status']) ? trim((string) $params['status']) : null,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $rooms = $this->queries->paginateReceptionRooms($params['search'] ?? null, $params['status'] ?? null, 50);
        $items = collect($rooms->items())->map(function ($r) {
            $status = $r->is_active ? 'actif' : 'inactif';

            return "{$r->name} ({$r->code}) — {$r->location} — {$status}";
        })->all();

        return AgentResponseFormatter::list('Salles d\'accueil', $items, $rooms->total());
    }
}
