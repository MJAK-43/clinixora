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

class ListDistrictsHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.list_districts';

    public function __construct(private readonly GeographyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les quartiers de Yaoundé';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les quartiers d’une ville';
    }

    public function validateParams(array $params): array
    {
        if (empty($params['city']) && empty($params['city_id'])) {
            throw ValidationException::withMessages(['city' => 'Indiquez la ville (ex. Yaoundé).']);
        }

        $city = $this->resolveCity($params);

        return [
            'city_id' => $city->id,
            'city_name' => $city->name,
            'search' => isset($params['search']) ? trim((string) $params['search']) : null,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $city = $this->resolveCity(['city_id' => $params['city_id']]);
        $districts = $this->queries->paginateDistricts($city, $params['search'] ?? null, 50);
        $items = collect($districts->items())->map(fn ($d) => "{$d->name} ({$d->code})")->all();

        return AgentResponseFormatter::list("Quartiers de {$city->name}", $items, $districts->total());
    }
}
