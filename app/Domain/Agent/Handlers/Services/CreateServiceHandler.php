<?php

namespace App\Domain\Agent\Handlers\Services;

use App\Domain\Agent\Concerns\AuthorizesServiceAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Services\Actions\CreateServiceAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateServiceHandler implements AgentActionHandler
{
    use AuthorizesServiceAgent;

    public const ACTION_KEY = 'services.create_service';

    public function __construct(private readonly CreateServiceAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer le service Urgences code URG';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageServices($user);
    }

    public function describe(array $params): string
    {
        return "Créer le service « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nom et code service requis.']);
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
            $service = $this->action->execute($params);
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer le service (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("Le service « {$service->name} » ({$service->code}) a été créé.");
    }
}
