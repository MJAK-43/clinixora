<?php

namespace App\Domain\Agent\Handlers\Specialties;

use App\Domain\Agent\Concerns\AuthorizesSpecialtyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Support\AgentResponseFormatter;
use App\Domain\Specialties\Services\SpecialtyQueryService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SearchSpecialtiesHandler implements AgentActionHandler
{
    use AuthorizesSpecialtyAgent;

    public const ACTION_KEY = 'specialties.search_specialties';

    public function __construct(private readonly SpecialtyQueryService $queries) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'rechercher la spécialité Cardiologie';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function describe(array $params): string
    {
        return "Rechercher une spécialité : « {$params['search']} »";
    }

    public function validateParams(array $params): array
    {
        $search = trim((string) ($params['search'] ?? $params['query'] ?? $params['name'] ?? ''));

        if ($search === '') {
            throw ValidationException::withMessages([
                'search' => 'Indiquez le nom ou le code de la spécialité à rechercher.',
            ]);
        }

        return ['search' => $search];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $search = $params['search'];
        $specialties = $this->queries->paginateSpecialties($search, null, 50);
        $items = collect($specialties->items())->map(fn ($s) => "{$s->name} ({$s->code})")->all();

        if ($items === []) {
            return new AgentActionResult("Aucune spécialité trouvée pour « {$search} ».");
        }

        return AgentResponseFormatter::list("Résultats spécialités pour « {$search} »", $items, $specialties->total());
    }
}
