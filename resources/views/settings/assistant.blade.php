<x-clinixora-layout title="Assistant Clinixora" :customize-widgets="$customizeWidgets" :assistant-messages="$assistantMessages">
<div class="min-w-0 flex-1 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8">
<div class="mx-auto max-w-4xl">
<div class="mb-6">
<a href="{{ route('parametres') }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">← Paramètres</a>
<h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Assistant Clinixora</h1>
<p class="mt-1 text-sm text-slate-600">Configurez le mode de l'assistant et consultez le catalogue d'actions disponibles.</p>
</div>
@if (session('success'))
<div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('parametres.assistant.update') }}" class="space-y-6">
@csrf
@method('PATCH')
<section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="text-base font-semibold text-slate-900">Mode de fonctionnement</h2>
<p class="mt-1 text-sm text-slate-500">Le mode MVP utilise des intentions prédéfinies ; le mode LLM sera activé ultérieurement.</p>
<div class="mt-4 space-y-3">
<label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-4 has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50/50">
<input type="radio" name="mode" value="mvp" class="mt-1 text-sky-600 focus:ring-sky-500" @checked(old('mode', $settings->mode) === 'mvp')>
<span><span class="font-medium text-slate-900">MVP (recommandé)</span><span class="mt-0.5 block text-sm text-slate-600">Commandes structurées, catalogue d'actions, confirmation pour les écritures.</span></span>
</label>
<label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-4 has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50/50">
<input type="radio" name="mode" value="llm" class="mt-1 text-sky-600 focus:ring-sky-500" @checked(old('mode', $settings->mode) === 'llm')>
<span><span class="font-medium text-slate-900">LLM (bientôt)</span><span class="mt-0.5 block text-sm text-slate-600">Interprétation en langage naturel — même catalogue et confirmations obligatoires.</span></span>
</label>
</div>
</section>
<section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="text-base font-semibold text-slate-900">Options</h2>
<div class="mt-4 space-y-4">
<label class="flex items-center justify-between gap-4">
<span class="text-sm text-slate-700">Activer l'assistant pour tous les utilisateurs autorisés</span>
<input type="hidden" name="assistant_enabled" value="0">
<input type="checkbox" name="assistant_enabled" value="1" @checked(old('assistant_enabled', $settings->assistant_enabled))>
</label>
<label class="flex items-center justify-between gap-4">
<span class="text-sm text-slate-700">Exiger une confirmation pour les actions d'écriture</span>
<input type="hidden" name="require_confirmation_writes" value="0">
<input type="checkbox" name="require_confirmation_writes" value="1" @checked(old('require_confirmation_writes', $settings->require_confirmation_writes))>
</label>
</div>
</section>
<div class="flex justify-end">
<button type="submit" class="rounded-lg bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-600">Enregistrer</button>
</div>
</form>
<section class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="text-base font-semibold text-slate-900">Catalogue d'actions</h2>
<ul class="mt-4 divide-y divide-slate-100">
@forelse ($actions as $action)
<li class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
<div>
<p class="font-medium text-slate-900">{{ $action->label }}</p>
<p class="text-xs text-slate-500">{{ $action->action_key }} — {{ $action->module }}</p>
</div>
<span class="text-xs font-medium {{ $action->is_enabled ? 'text-emerald-600' : 'text-slate-400' }}">{{ $action->is_enabled ? 'Activée' : 'Désactivée' }}@if ($action->requires_confirmation) · confirmation @endif</span>
</li>
@empty
<li class="py-4 text-sm text-slate-500">Aucune action enregistrée.</li>
@endforelse
</ul>
</section>
@if ($recentLogs->isNotEmpty())
<section class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="text-base font-semibold text-slate-900">Journal récent</h2>
<ul class="mt-4 space-y-2 text-sm">
@foreach ($recentLogs as $log)
<li class="flex flex-wrap gap-2 text-slate-600">
<span class="tabular-nums text-slate-400">{{ $log->created_at?->format('d/m H:i') }}</span>
<span class="font-medium text-slate-800">{{ $log->status }}</span>
@if ($log->action_key)<span>{{ $log->action_key }}</span>@endif
@if ($log->user)<span class="text-slate-400">— {{ $log->user->name }}</span>@endif
</li>
@endforeach
</ul>
</section>
@endif
</div>
</div>
</x-clinixora-layout>
