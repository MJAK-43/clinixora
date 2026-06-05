<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks;

use App\Domain\Agent\Concerns\AuthorizesOperatingBlockAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\OperatingBlocks\Concerns\ResolvesOperatingBlockEntities;
use App\Domain\OperatingBlocks\Actions\UpdateOperatingBlockAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateOperatingBlockHandler implements AgentActionHandler
{
    use AuthorizesOperatingBlockAgent;
    use ResolvesOperatingBlockEntities;

    public const ACTION_KEY = 'operating_blocks.update_operating_block';

    public function __construct(private readonly UpdateOperatingBlockAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier le bloc opératoire Bloc Central en Bloc Central code BLOC-01 service Chirurgie générale localisation Niveau 0';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function describe(array $params): string
    {
        return "Modifier le bloc « {$params['operating_block']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $block = $this->resolveOperatingBlock($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));
        $location = trim((string) ($params['location'] ?? $block->location));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'operating_block_id' => $block->id,
            'operating_block' => $block->name,
            'service_id' => $this->resolveServiceId($params),
            'name' => $name,
            'code' => $code,
            'location' => $location,
            'is_active' => $params['is_active'] ?? $block->is_active,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $block = $this->resolveOperatingBlock(['operating_block_id' => $params['operating_block_id']]);
        $updated = $this->action->execute($block, $params);

        return new AgentActionResult("Le bloc a été mis à jour : « {$updated->name} » ({$updated->code}).");
    }
}
