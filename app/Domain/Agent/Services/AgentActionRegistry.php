<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Models\AgentAction;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

class AgentActionRegistry
{
    public function __construct(
        private readonly Container $container,
    ) {}

    public function findAction(string $actionKey): ?AgentAction
    {
        return AgentAction::query()
            ->where('action_key', $actionKey)
            ->where('is_enabled', true)
            ->first();
    }

    public function resolveHandler(AgentAction $action): AgentActionHandler
    {
        $handler = $this->container->make($action->handler_class);

        if (! $handler instanceof AgentActionHandler) {
            throw new InvalidArgumentException("Handler invalide pour {$action->action_key}.");
        }

        if ($handler->actionKey() !== $action->action_key) {
            throw new InvalidArgumentException("Clé d'action incohérente pour {$action->action_key}.");
        }

        return $handler;
    }
}
