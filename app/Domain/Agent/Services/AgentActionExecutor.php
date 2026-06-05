<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Exceptions\AgentActionDeniedException;
use App\Domain\Agent\Exceptions\AssistantDisabledException;
use App\Models\AssistantSetting;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class AgentActionExecutor
{
    public function __construct(
        private readonly AgentActionRegistry $registry,
        private readonly AgentAuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $params
     */
    public function execute(User $user, string $actionKey, array $params): AgentActionResult
    {
        $settings = AssistantSetting::current();

        if (! $settings->assistant_enabled) {
            throw new AssistantDisabledException('L’assistant est désactivé par l’administrateur.');
        }

        if ($settings->mode === AssistantSetting::MODE_LLM) {
            throw new AssistantDisabledException('Le mode LLM n’est pas encore disponible. Utilisez le mode MVP.');
        }

        $action = $this->registry->findAction($actionKey);

        if ($action === null) {
            throw new AgentActionDeniedException('Action inconnue ou désactivée.');
        }

        $handler = $this->registry->resolveHandler($action);

        if (! $handler->authorize($user)) {
            $this->audit->log($user, 'denied', $actionKey, $params, 'Permission refusée.');
            throw new AuthorizationException('Vous n’avez pas la permission d’exécuter cette action.');
        }

        try {
            $validated = $handler->validateParams($params);
            $result = $handler->execute($user, $validated);
            $this->audit->log($user, 'success', $actionKey, $validated, $result->message);

            return $result;
        } catch (ValidationException $e) {
            $this->audit->log($user, 'failed', $actionKey, $params, $e->getMessage());
            throw $e;
        } catch (\Throwable $e) {
            $this->audit->log($user, 'failed', $actionKey, $params, $e->getMessage());
            throw $e;
        }
    }
}
