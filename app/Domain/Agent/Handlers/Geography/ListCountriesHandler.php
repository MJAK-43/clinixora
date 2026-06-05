<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Geography\Services\GeographyQueryService;
use App\Models\User;

class ListCountriesHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;

    public const ACTION_KEY = 'geography.list_countries';

    public function __construct(private readonly GeographyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les pays';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les pays';
    }

    public function validateParams(array $params): array
    {
        return [
            'search' => isset($params['search']) ? trim((string) $params['search']) : null,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $countries = $this->queries->paginateCountries($params['search'] ?? null, 50);
        $items = collect($countries->items())->map(fn ($c) => "{$c->name} ({$c->code})")->all();

        return AgentResponseFormatter::list('Pays', $items, $countries->total());
    }
}
