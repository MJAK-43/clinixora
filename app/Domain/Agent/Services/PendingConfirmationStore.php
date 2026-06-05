<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\DTO\PendingConfirmation;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PendingConfirmationStore
{
    private const TTL_SECONDS = 900;

    public function create(User $user, string $actionKey, array $params, string $summary): PendingConfirmation
    {
        $token = Str::uuid()->toString();
        $pending = new PendingConfirmation($token, $actionKey, $params, $summary);

        Cache::put($this->cacheKey($user, $token), $pending->toArray(), self::TTL_SECONDS);

        return $pending;
    }

    public function pull(User $user, string $token): ?PendingConfirmation
    {
        $key = $this->cacheKey($user, $token);
        $data = Cache::pull($key);

        if (! is_array($data)) {
            return null;
        }

        return new PendingConfirmation(
            $data['token'],
            $data['action_key'],
            $data['params'] ?? [],
            $data['summary'] ?? '',
        );
    }

    private function cacheKey(User $user, string $token): string
    {
        return "agent_pending:{$user->id}:{$token}";
    }
}
