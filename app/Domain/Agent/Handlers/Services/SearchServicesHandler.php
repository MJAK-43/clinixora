<?php

namespace App\Domain\Agent\Handlers\Services;

use App\Domain\Agent\Concerns\AuthorizesServiceAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Services\Services\ServiceQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchServicesHandler implements AgentActionHandler
{
    use AuthorizesServiceAgent;

    public const ACTION_KEY = 'services.search_services';

    public function __construct(private readonly ServiceQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher le service Urgences';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher un service : « {$params['search']} »";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code du service à rechercher.',
            ]);
        }

        return ['search' => $search];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $search = $params['search'];
        $services = $this->queries->paginateServices($search, null, 50);
        $items = collect($services->items())->map(fn ($s) => "{$s->name} ({$s->code})")->all();

        if ($items === []) {
            return new AgentActionResult("Aucun service trouvé pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats services pour « {$search} »", $items, $services->total());
    }
}
