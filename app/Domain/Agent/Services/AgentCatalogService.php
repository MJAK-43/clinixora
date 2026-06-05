<?php

namespace App\Domain\Agent\Services;

use App\Models\AgentAction;
use App\Models\User;

class AgentCatalogService
{
    public function __construct(
        private readonly AgentActionRegistry $registry,
    ) {}

    /**
     * @return list<array{key: string, label: string, description: string, actions: list<array{action_key: string, label: string, description: string|null, syntax: string, requires_confirmation: bool}>}>
     */
    public function catalogForUser(User $user): array
    {
        $actions = AgentAction::query()
            ->where('is_enabled', true)
            ->orderBy('module')
            ->orderBy('label')
            ->get();

        /** @var array<string, array{key: string, label: string, description: string, actions: list<array<string, mixed>>}> $grouped */
        $grouped = [];

        foreach ($actions as $action) {
            $handler = $this->registry->resolveHandler($action);

            if (! $handler->authorize($user)) {
                continue;
            }

            $moduleKey = $action->module;
            $moduleMeta = config("agent.modules.{$moduleKey}", [
                'label' => ucfirst($moduleKey),
                'description' => '',
            ]);

            if (! isset($grouped[$moduleKey])) {
                $grouped[$moduleKey] = [
                    'key' => $moduleKey,
                    'label' => $moduleMeta['label'],
                    'description' => $moduleMeta['description'] ?? '',
                    'actions' => [],
                ];
            }

            $grouped[$moduleKey]['actions'][] = [
                'action_key' => $action->action_key,
                'label' => $action->label,
                'description' => $action->description,
                'syntax' => $handler->syntaxTemplate(),
                'requires_confirmation' => $action->requires_confirmation,
            ];
        }

        return array_values($grouped);
    }
}
