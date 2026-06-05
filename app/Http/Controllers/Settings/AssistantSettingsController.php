<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AgentAction;
use App\Models\AgentAuditLog;
use App\Models\AssistantSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssistantSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $this->authorize('viewAny', AssistantSetting::class);

        return view('settings.assistant', [
            'customizeWidgets' => [
                ['id' => 'assistant_panel', 'label' => 'Assistant Clinixora', 'default' => true],
            ],
            'assistantMessages' => [],
            'settings' => AssistantSetting::current(),
            'actions' => AgentAction::query()->orderBy('module')->orderBy('label')->get(),
            'recentLogs' => AgentAuditLog::query()
                ->with('user')
                ->latest('created_at')
                ->limit(15)
                ->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('update', AssistantSetting::class);

        $validated = $request->validate([
            'mode' => ['required', 'in:mvp,llm'],
            'assistant_enabled' => ['sometimes', 'boolean'],
            'require_confirmation_writes' => ['sometimes', 'boolean'],
        ]);

        $settings = AssistantSetting::current();
        $settings->fill([
            'mode' => $validated['mode'],
            'assistant_enabled' => $request->boolean('assistant_enabled'),
            'require_confirmation_writes' => $request->boolean('require_confirmation_writes'),
            'updated_by' => $request->user()->id,
        ]);
        $settings->save();

        return redirect()
            ->route('parametres.assistant')
            ->with('success', 'Paramètres de l’assistant enregistrés.');
    }
}
