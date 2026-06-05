<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks;

use App\Domain\Agent\Concerns\AuthorizesOperatingBlockAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\OperatingBlocks\Services\OperatingBlockQueryService;
use App\Models\User;

class ListOperatingBlocksHandler implements AgentActionHandler
{
    use AuthorizesOperatingBlockAgent;

    public const ACTION_KEY = 'operating_blocks.list_operating_blocks';

    public function __construct(private readonly OperatingBlockQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les blocs opératoires';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les blocs opératoires';
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
        $blocks = $this->queries->paginateOperatingBlocks($params['search'] ?? null, $params['status'] ?? null, null, 50);
        $items = collect($blocks->items())->map(function ($b) {
            $status = $b->is_active ? 'actif' : 'inactif';

            return "{$b->name} ({$b->code}) — {$b->service?->name} — {$status}";
        })->all();

        return AgentResponseFormatter::list('Blocs opératoires', $items, $blocks->total());
    }
}
