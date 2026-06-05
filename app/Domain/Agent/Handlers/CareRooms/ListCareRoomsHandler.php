<?php

namespace App\Domain\Agent\Handlers\CareRooms;

use App\Domain\Agent\Concerns\AuthorizesCareRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\CareRooms\Services\CareRoomQueryService;
use App\Models\User;

class ListCareRoomsHandler implements AgentActionHandler
{
    use AuthorizesCareRoomAgent;

    public const ACTION_KEY = 'care_rooms.list_care_rooms';

    public function __construct(private readonly CareRoomQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les salles de soin';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les salles de soin';
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
        $rooms = $this->queries->paginateCareRooms($params['search'] ?? null, $params['status'] ?? null, null, 50);
        $items = collect($rooms->items())->map(function ($r) {
            $status = $r->is_active ? 'actif' : 'inactif';

            return "{$r->name} ({$r->code}) — {$r->service?->name} — {$status}";
        })->all();

        return AgentResponseFormatter::list('Salles de soin', $items, $rooms->total());
    }
}
