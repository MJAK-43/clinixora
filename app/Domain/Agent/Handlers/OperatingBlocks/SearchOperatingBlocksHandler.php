<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks;

use App\Domain\Agent\Concerns\AuthorizesOperatingBlockAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\OperatingBlocks\Services\OperatingBlockQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchOperatingBlocksHandler implements AgentActionHandler
{
    use AuthorizesOperatingBlockAgent;

    public const ACTION_KEY = 'operating_blocks.search_operating_blocks';

    public function __construct(private readonly OperatingBlockQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher le bloc opératoire Bloc Central';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher un bloc opératoire : « {$params['search']} »";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code du bloc à rechercher.',
            ]);
        }

        return ['search' => $search];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $search = $params['search'];
        $blocks = $this->queries->paginateOperatingBlocks($search, null, null, 50);
        $items = collect($blocks->items())->map(fn ($b) => "{$b->name} ({$b->code}) — {$b->service?->name}")->all();

        if ($items === []) {
            return new AgentActionResult("Aucun bloc trouvé pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats blocs pour « {$search} »", $items, $blocks->total());
    }
}
