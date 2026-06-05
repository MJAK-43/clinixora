<?php

namespace App\Domain\Agent\Handlers\Services;

use App\Domain\Agent\Concerns\AuthorizesServiceAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Services\Concerns\ResolvesServiceEntities;
use App\Domain\Services\Actions\DeleteServiceAction;
use App\Models\User;

class DeleteServiceHandler implements AgentActionHandler
{
    use AuthorizesServiceAgent;
    use ResolvesServiceEntities;

    public const ACTION_KEY = 'services.delete_service';

    public function __construct(private readonly DeleteServiceAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer le service Urgences';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer le service « {$params['service']} »";
    }

    public function validateParams(array $params): array
    {
        $service = $this->resolveService($params);

        return ['service_id' => $service->id, 'service' => $service->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $service = $this->resolveService(['service_id' => $params['service_id']]);
        $this->action->execute($service);

        return new AgentActionResult("Le service « {$service->name} » a été supprimé.");
    }
}
