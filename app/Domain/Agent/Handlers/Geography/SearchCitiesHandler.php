<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchCitiesHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.search_cities';

    public function __construct(private readonly GeographyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher la ville Yaoun dans Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher une ville « {$params['search']} » dans {$params['country_name']}";
    }

    public function validateParams(array $params): array
    {
        $country = $this->resolveCountry($params);
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? $params['city'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code de la ville à rechercher.',
            ]);
        }

        return [
            'country_id' => $country->id,
            'country_name' => $country->name,
            'search' => $search,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $country = $this->resolveCountry(['country_id' => $params['country_id']]);
        $search = $params['search'];
        $cities = $this->queries->paginateCities($country, $search, 50);
        $items = collect($cities->items())->map(fn ($city) => "{$city->name} ({$city->code})")->all();

        if ($items === []) {
            return new AgentActionResult("Aucune ville trouvée pour « {$search} » dans {$country->name}.");
        }

        return AgentResponseFormatter::list(
            "Villes trouvées pour « {$search} » ({$country->name})",
            $items,
            $cities->total(),
        );
    }
}
