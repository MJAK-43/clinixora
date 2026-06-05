<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks;

use App\Domain\Agent\Concerns\AuthorizesOperatingBlockAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\OperatingBlocks\Concerns\ResolvesOperatingBlockEntities;
use App\Domain\OperatingBlocks\Actions\CreateOperatingBlockAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateOperatingBlockHandler implements AgentActionHandler
{
    use AuthorizesOperatingBlockAgent;
    use ResolvesOperatingBlockEntities;

    public const ACTION_KEY = 'operating_blocks.create_operating_block';

    public function __construct(private readonly CreateOperatingBlockAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer le bloc opératoire Bloc Central code BLOC-01 service Chirurgie générale localisation Niveau 0';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function describe(array $params): string
    {
        return "Créer le bloc « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));
        $location = trim((string) ($params['location'] ?? ''));

        if ($name === '' || $code === '' || $location === '') {
            throw ValidationException::withMessages(['name' => 'Nom, code et localisation requis.']);
        }

        return [
            'service_id' => $this->resolveServiceId($params),
            'name' => $name,
            'code' => $code,
            'location' => $location,
            'is_active' => $params['is_active'] ?? true,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        try {
            $block = $this->action->execute($params);
            $block->load('service');
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer le bloc (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("Le bloc « {$block->name} » ({$block->code}) a été créé pour {$block->service?->name}.");
    }
}
