<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\DTO\AgentActionResult;
use App\Models\AgentAction;
use App\Models\AssistantSetting;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class AgentChatService
{
    public function __construct(
        private readonly IntentRouter $intentRouter,
        private readonly AgentActionRegistry $registry,
        private readonly AgentActionExecutor $executor,
        private readonly PendingConfirmationStore $pendingStore,
        private readonly AgentAuditLogger $audit,
    ) {}

    /**
     * @return array{reply: string, pending?: array<string, mixed>, display?: array<string, mixed>, reset_explorer?: bool}
     */
    public function handleMessage(User $user, string $message): array
    {
        $settings = AssistantSetting::current();

        if (! $settings->assistant_enabled) {
            return ['reply' => 'L’assistant Clinixora est actuellement désactivé. Contactez un administrateur.'];
        }

        if ($settings->mode === AssistantSetting::MODE_LLM) {
            return ['reply' => 'Le mode LLM sera disponible prochainement. Passez en mode MVP dans Paramètres → Assistant.'];
        }

        $intent = $this->intentRouter->match($message);

        if ($intent === null) {
            $this->audit->log($user, 'unmatched', message: $message);

            return ['reply' => $this->fallbackMessage()];
        }

        if ($intent['action_key'] === '_help') {
            return ['reply' => $this->helpMessage()];
        }

        $action = $this->registry->findAction($intent['action_key']);

        if ($action === null) {
            return ['reply' => 'Cette action n’est pas disponible pour le moment.'];
        }

        $handler = $this->registry->resolveHandler($action);

        if (! $handler->authorize($user)) {
            $this->audit->log($user, 'denied', $intent['action_key'], $intent['params'], 'Permission refusée.');

            return ['reply' => 'Vous n’avez pas la permission d’effectuer cette action.'];
        }

        try {
            $params = $handler->validateParams($intent['params']);
        } catch (ValidationException $e) {
            return ['reply' => collect($e->errors())->flatten()->first() ?? 'Paramètres invalides.'];
        }

        $needsConfirmation = $action->requires_confirmation
            && $settings->require_confirmation_writes
            && $this->isWriteAction($action);

        if ($needsConfirmation) {
            $summary = $handler->describe($params);
            $pending = $this->pendingStore->create($user, $action->action_key, $params, $summary);
            $this->audit->log($user, 'pending', $action->action_key, $params, $summary);

            return [
                'reply' => "Je vais : {$summary}.\n\nConfirmez-vous cette action ?",
                'pending' => $pending->toArray(),
            ];
        }

        $result = $this->executor->execute($user, $action->action_key, $params);

        return $this->buildPayload($result, resetExplorer: true);
    }

    /**
     * @return array{reply: string, display?: array<string, mixed>, reset_explorer?: bool}
     */
    public function confirm(User $user, string $token): array
    {
        $pending = $this->pendingStore->pull($user, $token);

        if ($pending === null) {
            return ['reply' => 'Cette confirmation a expiré ou a déjà été traitée. Reformulez votre demande.'];
        }

        try {
            $result = $this->executor->execute($user, $pending->actionKey, $pending->params);

            return $this->buildPayload($result, resetExplorer: true);
        } catch (AuthorizationException) {
            return ['reply' => 'Action refusée : permissions insuffisantes.'];
        } catch (ValidationException $e) {
            return ['reply' => collect($e->errors())->flatten()->first() ?? 'Échec de l’action.'];
        } catch (\Throwable) {
            return ['reply' => 'Une erreur est survenue lors de l’exécution.'];
        }
    }

    /**
     * @return array{reply: string, display?: array<string, mixed>, reset_explorer?: bool}
     */
    private function buildPayload(AgentActionResult $result, bool $resetExplorer = false): array
    {
        $payload = ['reply' => $result->message];

        if (! empty($result->data['list_items'])) {
            $payload['display'] = [
                'type' => 'list',
                'title' => $result->data['title'] ?? $result->message,
                'items' => $result->data['list_items'],
                'total' => $result->data['total'] ?? count($result->data['list_items']),
                'preview_limit' => $result->data['preview_limit'] ?? 6,
                'columns' => 2,
            ];
        }

        if ($resetExplorer) {
            $payload['reset_explorer'] = true;
        }

        return $payload;
    }

    private function isWriteAction(AgentAction $action): bool
    {
        return str_contains($action->action_key, '.create_')
            || str_contains($action->action_key, '.update_')
            || str_contains($action->action_key, '.delete_');
    }

    private function helpMessage(): string
    {
        return implode("\n", [
            'Utilisez le catalogue en haut : module → action → syntaxe.',
            'Exemples : « liste les pays », « créer la ville X code Y dans Cameroun », « supprimer le quartier Z à Yaoundé ».',
        ]);
    }

    private function fallbackMessage(): string
    {
        return 'Commande non reconnue. Choisissez une action dans le catalogue ou tapez « aide ».';
    }
}
