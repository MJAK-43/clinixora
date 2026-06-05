<?php

namespace App\Http\Controllers\Agent;

use App\Domain\Agent\Services\AgentChatService;
use App\Http\Controllers\Controller;
use App\Models\AssistantSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentChatController extends Controller
{
    public function store(Request $request, AgentChatService $chat): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $result = $chat->handleMessage($request->user(), $validated['message']);

        return response()->json([
            'reply' => $result['reply'],
            'pending' => $result['pending'] ?? null,
            'display' => $result['display'] ?? null,
            'reset_explorer' => $result['reset_explorer'] ?? false,
            'mode' => AssistantSetting::current()->mode,
        ]);
    }

    public function confirm(Request $request, AgentChatService $chat): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'uuid'],
        ]);

        $result = $chat->confirm($request->user(), $validated['token']);

        return response()->json([
            'reply' => $result['reply'],
            'display' => $result['display'] ?? null,
            'reset_explorer' => $result['reset_explorer'] ?? false,
        ]);
    }
}
