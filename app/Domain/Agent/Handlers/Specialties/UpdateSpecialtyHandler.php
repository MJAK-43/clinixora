<?php

namespace App\Domain\Agent\Handlers\Specialties;

use App\Domain\Agent\Concerns\AuthorizesSpecialtyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Specialties\Concerns\ResolvesSpecialtyEntities;
use App\Domain\Specialties\Actions\UpdateSpecialtyAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateSpecialtyHandler implements AgentActionHandler
{
    use AuthorizesSpecialtyAgent;
    use ResolvesSpecialtyEntities;

    public const ACTION_KEY = 'specialties.update_specialty';

    public function __construct(private readonly UpdateSpecialtyAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier la spécialité Cardiologie en Cardiologie code CARD';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function describe(array $params): string
    {
        return "Modifier la spécialité « {$params['specialty']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $specialty = $this->resolveSpecialty($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'specialty_id' => $specialty->id,
            'specialty' => $specialty->name,
            'name' => $name,
            'code' => $code,
            'description' => array_key_exists('description', $params) ? trim((string) $params['description']) : $specialty->description,
            'is_active' => $params['is_active'] ?? $specialty->is_active,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $specialty = $this->resolveSpecialty(['specialty_id' => $params['specialty_id']]);
        $updated = $this->action->execute($specialty, $params);

        return new AgentActionResult("La spécialité a été mise à jour : « {$updated->name} » ({$updated->code}).");
    }
}
