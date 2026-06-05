<?php

namespace App\Domain\Agent\Handlers\ReceptionRooms;

use App\Domain\Agent\Concerns\AuthorizesReceptionRoomAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\ReceptionRooms\Services\ReceptionRoomQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchReceptionRoomsHandler implements AgentActionHandler
{
    use AuthorizesReceptionRoomAgent;

    public const ACTION_KEY = 'reception_rooms.search_reception_rooms';

    public function __construct(private readonly ReceptionRoomQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher la salle d\'accueil Accueil Principal';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageReceptionRooms($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher une salle d'accueil : « {$params['search']} »";
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
        $rooms = $this->queries->paginateReceptionRooms($search, null, 50);
        $items = collect($rooms->items())->map(fn ($r) => "{$r->name} ({$r->code}) — {$r->location}")->all();

        if ($items === []) {
            return new AgentActionResult("Aucune salle d'accueil trouvée pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats salles d'accueil pour « {$search} »", $items, $rooms->total());
    }
}
