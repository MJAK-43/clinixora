<?php

namespace App\Domain\Agent\Handlers\CareRooms;

use App\Domain\Agent\Concerns\AuthorizesCareRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\CareRooms\Services\CareRoomQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchCareRoomsHandler implements AgentActionHandler
{
    use AuthorizesCareRoomAgent;

    public const ACTION_KEY = 'care_rooms.search_care_rooms';

    public function __construct(private readonly CareRoomQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher la salle de soin Salle Infirmière';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageCareRooms($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher une salle de soin : « {$params['search']} »";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code de la salle à rechercher.',
            ]);
        }

        return ['search' => $search];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $search = $params['search'];
        $rooms = $this->queries->paginateCareRooms($search, null, null, 50);
        $items = collect($rooms->items())->map(fn ($r) => "{$r->name} ({$r->code}) — {$r->service?->name}")->all();

        if ($items === []) {
            return new AgentActionResult("Aucune salle de soin trouvée pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats salles de soin pour « {$search} »", $items, $rooms->total());
    }
}
