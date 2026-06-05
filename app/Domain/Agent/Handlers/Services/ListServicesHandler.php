<?php

namespace App\Domain\Agent\Handlers\Services;

use App\Domain\Agent\Concerns\AuthorizesServiceAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Services\Services\ServiceQueryService;
use App\Models\User;

class ListServicesHandler implements AgentActionHandler
{
    use AuthorizesServiceAgent;

    public const ACTION_KEY = 'services.list_services';

    public function __construct(private readonly ServiceQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les services';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les services';
    }

    public function validateParams(array $params): array
    {
        return [
            'search' => isset($params['search']) ? trim((string) $params['search']) : null,
            'status' => isset($params['status']) ? trim((string) $params['status']) : null,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $services = $this->queries->paginateServices($params['search'] ?? null, $params['status'] ?? null, 50);
        $items = collect($services->items())->map(function ($s) {
            $status = $s->is_active ? 'actif' : 'inactif';

            return "{$s->name} ({$s->code}) — {$status}";
        })->all();

        return AgentResponseFormatter::list('Services', $items, $services->total());
    }
}
