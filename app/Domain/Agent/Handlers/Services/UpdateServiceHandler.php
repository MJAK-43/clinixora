<?php

namespace App\Domain\Agent\Handlers\Services;

use App\Domain\Agent\Concerns\AuthorizesServiceAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Services\Concerns\ResolvesServiceEntities;
use App\Domain\Services\Actions\UpdateServiceAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateServiceHandler implements AgentActionHandler
{
    use AuthorizesServiceAgent;
    use ResolvesServiceEntities;

    public const ACTION_KEY = 'services.update_service';

    public function __construct(private readonly UpdateServiceAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier le service Urgences en Urgences code URG';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function describe(array $params): string
    {
        return "Modifier le service « {$params['service']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $service = $this->resolveService($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'service_id' => $service->id,
            'service' => $service->name,
            'name' => $name,
            'code' => $code,
            'description' => array_key_exists('description', $params) ? trim((string) $params['description']) : $service->description,
            'is_active' => $params['is_active'] ?? $service->is_active,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $service = $this->resolveService(['service_id' => $params['service_id']]);
        $updated = $this->action->execute($service, $params);

        return new AgentActionResult("Le service a été mis à jour : « {$updated->name} » ({$updated->code}).");
    }
}
