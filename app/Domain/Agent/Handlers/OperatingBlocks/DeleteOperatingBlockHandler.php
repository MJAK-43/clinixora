<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks;

use App\Domain\Agent\Concerns\AuthorizesOperatingBlockAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\OperatingBlocks\Concerns\ResolvesOperatingBlockEntities;
use App\Domain\OperatingBlocks\Actions\DeleteOperatingBlockAction;
use App\Models\User;

class DeleteOperatingBlockHandler implements AgentActionHandler
{
    use AuthorizesOperatingBlockAgent;
    use ResolvesOperatingBlockEntities;

    public const ACTION_KEY = 'operating_blocks.delete_operating_block';

    public function __construct(private readonly DeleteOperatingBlockAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer le bloc opératoire Bloc Central';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageOperatingBlocks($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer le bloc « {$params['operating_block']} »";
    }

    public function validateParams(array $params): array
    {
        $block = $this->resolveOperatingBlock($params);

        return ['operating_block_id' => $block->id, 'operating_block' => $block->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $block = $this->resolveOperatingBlock(['operating_block_id' => $params['operating_block_id']]);
        $this->action->execute($block);

        return new AgentActionResult("Le bloc « {$block->name} » a été supprimé.");
    }
}
