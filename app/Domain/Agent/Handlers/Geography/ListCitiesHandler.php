<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Models\User;

class ListCitiesHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.list_cities';

    public function __construct(
        private readonly GeographyQueryService $queries,
    ) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les villes du Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les villes d’un pays';
    }

    public function validateParams(array $params): array
    {
        $country = $this->resolveCountry($params);

        return [
            'country_id' => $country->id,
            'country_name' => $country->name,
            'search' => isset($params['search']) ? trim((string) $params['search']) : null,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $country = $this->resolveCountry(['country_id' => $params['country_id']]);
        $cities = $this->queries->paginateCities($country, $params['search'] ?? null, 50);
        $items = collect($cities->items())->map(fn ($city) => "{$city->name} ({$city->code})")->all();

        return AgentResponseFormatter::list("Villes de {$country->name}", $items, $cities->total());
    }
}
