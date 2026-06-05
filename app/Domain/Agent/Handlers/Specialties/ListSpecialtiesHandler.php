<?php

namespace App\Domain\Agent\Handlers\Specialties;

use App\Domain\Agent\Concerns\AuthorizesSpecialtyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Specialties\Services\SpecialtyQueryService;
use App\Models\User;

class ListSpecialtiesHandler implements AgentActionHandler
{
    use AuthorizesSpecialtyAgent;

    public const ACTION_KEY = 'specialties.list_specialties';

    public function __construct(private readonly SpecialtyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'liste les spécialités';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function describe(array $params): string
    {
        return 'Lister les spécialités';
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
        $specialties = $this->queries->paginateSpecialties($params['search'] ?? null, $params['status'] ?? null, 50);
        $items = collect($specialties->items())->map(function ($s) {
            $status = $s->is_active ? 'actif' : 'inactif';

            return "{$s->name} ({$s->code}) — {$status}";
        })->all();

        return AgentResponseFormatter::list('Spécialités', $items, $specialties->total());
    }
}
