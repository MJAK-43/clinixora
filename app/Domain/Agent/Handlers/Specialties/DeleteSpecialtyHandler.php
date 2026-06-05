<?php

namespace App\Domain\Agent\Handlers\Specialties;

use App\Domain\Agent\Concerns\AuthorizesSpecialtyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Specialties\Concerns\ResolvesSpecialtyEntities;
use App\Domain\Specialties\Actions\DeleteSpecialtyAction;
use App\Models\User;

class DeleteSpecialtyHandler implements AgentActionHandler
{
    use AuthorizesSpecialtyAgent;
    use ResolvesSpecialtyEntities;

    public const ACTION_KEY = 'specialties.delete_specialty';

    public function __construct(private readonly DeleteSpecialtyAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer la spécialité Cardiologie';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageSpecialties($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer la spécialité « {$params['specialty']} »";
    }

    public function validateParams(array $params): array
    {
        $specialty = $this->resolveSpecialty($params);

        return ['specialty_id' => $specialty->id, 'specialty' => $specialty->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $specialty = $this->resolveSpecialty(['specialty_id' => $params['specialty_id']]);
        $this->action->execute($specialty);

        return new AgentActionResult("La spécialité « {$specialty->name} » a été supprimée.");
    }
}
