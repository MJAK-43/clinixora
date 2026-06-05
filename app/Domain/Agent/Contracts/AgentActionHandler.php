<?php

namespace App\Domain\Agent\Contracts;

use App\Domain\Agent\DTO\AgentActionResult;
use App\Models\User;

interface AgentActionHandler
{
    public function actionKey(): string;

    /**
     * Exemple de phrase à saisir dans l’assistant (mode MVP).
     */
    public function syntaxTemplate(): string;

    public function authorize(User $user): bool;

    /**
     * @param  array<string, mixed>  $params
     */
    public function describe(array $params): string;

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function validateParams(array $params): array;

    /**
     * @param  array<string, mixed>  $params
     */
    public function execute(User $user, array $params): AgentActionResult;
}
