<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchCountriesHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;

    public const ACTION_KEY = 'geography.search_countries';

    public function __construct(private readonly GeographyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher le pays Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher un pays : « {$params['search']} »";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code du pays à rechercher.',
            ]);
        }

        return ['search' => $search];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $search = $params['search'];
        $countries = $this->queries->paginateCountries($search, 50);
        $items = collect($countries->items())->map(fn ($c) => "{$c->name} ({$c->code})")->all();

        if ($items === []) {
            return new AgentActionResult("Aucun pays trouvé pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats pays pour « {$search} »", $items, $countries->total());
    }
}
