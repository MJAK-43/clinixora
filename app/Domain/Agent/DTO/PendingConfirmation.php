<?php

namespace App\Domain\Agent\DTO;

readonly class PendingConfirmation
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public string $token,
        public string $actionKey,
        public array $params,
        public string $summary,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'action_key' => $this->actionKey,
            'params' => $this->params,
            'summary' => $this->summary,
        ];
    }
}
