<?php

namespace App\Domain\Agent\DTO;

readonly class AgentActionResult
{
    /**
     * @param  array<string, mixed>|null  $data
     */
    public function __construct(
        public string $message,
        public ?array $data = null,
    ) {}
}
