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

class SearchDistrictsHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.search_districts';

    public function __construct(private readonly GeographyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher le quartier Bastos à Yaoundé';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher un quartier « {$params['search']} » à {$params['city_name']}";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? $params['district'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code du quartier à rechercher.',
            ]);
        }

        if (empty($params['city']) && empty($params['city_id'])) {
            throw ValidationException::withMessages([
                'city' => 'Indiquez la ville (ex. Yaoundé).',
            ]);
        }

        $city = $this->resolveCity($params);

        return [
            'city_id' => $city->id,
            'city_name' => $city->name,
            'search' => $search,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $city = $this->resolveCity(['city_id' => $params['city_id']]);
        $search = $params['search'];
        $districts = $this->queries->paginateDistricts($city, $search, 50);
        $items = collect($districts->items())->map(fn ($d) => "{$d->name} ({$d->code})")->all();

        if ($items === []) {
            return new AgentActionResult("Aucun quartier trouvé pour « {$search} » à {$city->name}.");
        }

        return AgentResponseFormatter::list(
            "Quartiers trouvés pour « {$search} » ({$city->name})",
            $items,
            $districts->total(),
        );
    }
}
