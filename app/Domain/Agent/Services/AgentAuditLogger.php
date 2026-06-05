<?php

namespace App\Domain\Agent\Services;

use App\Models\AgentAuditLog;
use App\Models\AssistantSetting;
use App\Models\User;

class AgentAuditLogger
{
    /**
     * @param  array<string, mixed>|null  $payload
     */
    public function log(
        ?User $user,
        string $status,
        ?string $actionKey = null,
        ?array $payload = null,
        ?string $message = null,
        string $channel = 'app',
    ): AgentAuditLog {
        $settings = AssistantSetting::current();

        return AgentAuditLog::query()->create([
            'user_id' => $user?->id,
            'channel' => $channel,
            'mode' => $settings->mode,
            'action_key' => $actionKey,
            'status' => $status,
            'payload' => $payload,
            'message' => $message,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
