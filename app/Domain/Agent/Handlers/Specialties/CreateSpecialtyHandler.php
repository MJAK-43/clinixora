<?php

namespace App\Domain\Agent\Handlers\Specialties;

use App\Domain\Agent\Concerns\AuthorizesSpecialtyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Specialties\Actions\CreateSpecialtyAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateSpecialtyHandler implements AgentActionHandler
{
    use AuthorizesSpecialtyAgent;

    public const ACTION_KEY = 'specialties.create_specialty';

    public function __construct(private readonly CreateSpecialtyAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer la spécialité Cardiologie code CARD';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function describe(array $params): string
    {
        return "Créer la spécialité « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nom et code spécialité requis.']);
        }

        return [
            'name' => $name,
            'code' => $code,
            'description' => isset($params['description']) ? trim((string) $params['description']) : null,
            'is_active' => $params['is_active'] ?? true,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        try {
            $specialty = $this->action->execute($params);
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer la spécialité (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("La spécialité « {$specialty->name} » ({$specialty->code}) a été créée.");
    }
}
