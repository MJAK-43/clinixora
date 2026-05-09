@php
    $quickActions = [
        ['title' => 'Rechercher un patient', 'text' => 'Retrouver rapidement un dossier.', 'icon' => 'search'],
        ['title' => 'Rendez-vous du jour', 'text' => 'Voir les rendez-vous programmés.', 'icon' => 'calendar'],
        ['title' => 'Statistiques', 'text' => 'Consulter les chiffres clés.', 'icon' => 'chart'],
        ['title' => 'Stock faible', 'text' => 'Afficher les produits critiques.', 'icon' => 'cube'],
    ];
@endphp

{{-- Desktop / grand écran : panneau en overlay (ne réserve plus de place dans le flex) --}}
<template x-teleport="body">
<div
    x-show="widgets['assistant_panel'] && assistantOpen && !isCompactScreen"
    x-cloak
    class="fixed inset-0 z-[90]"
    @keydown.escape.window="assistantOpen = false"
>
    <div
        class="absolute inset-0 bg-slate-900/40"
        aria-hidden="true"
        @click="assistantOpen = false"
    ></div>
    <aside
        @click.stop
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute inset-y-0 right-0 flex max-h-[100dvh] flex-col border-l border-slate-200 bg-white shadow-2xl"
        style="width: min(40rem, 92vw); padding-bottom: env(safe-area-inset-bottom);"
    >
        <div class="flex min-h-0 flex-1 flex-col">
            <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-4 py-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/headbot.png') }}" alt="Assistant" class="h-11 w-11 rounded-full object-cover">
                    <div>
                        <p class="text-xl font-bold text-slate-900">CliniXora <span class="text-sky-600">Assistant</span></p>
                        <p class="text-sm text-slate-500"><span class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span>En ligne</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded p-1.5 text-slate-500 hover:bg-slate-100" @click="assistantOpen = false" title="Réduire">—</button>
                    <button type="button" class="rounded p-1.5 text-slate-500 hover:bg-slate-100" @click="assistantOpen = false" title="Fermer">✕</button>
                </div>
            </div>

            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto bg-[#F6FAFF] p-4">
                <div class="grid grid-cols-[80px_1fr] gap-3 rounded-2xl bg-white p-3 shadow-sm">
                    <img src="{{ asset('images/bot.png') }}" alt="Bot" class="h-20 w-20 rounded-xl object-cover">
                    <div class="rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-700">
                        <p class="font-semibold">Bonjour ! 👋</p>
                        <p>Je suis votre assistant <span class="font-semibold text-sky-600">CliniXora</span>. Comment puis-je vous aider aujourd’hui ?</p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ($quickActions as $action)
                        <button type="button" class="rounded-2xl border border-slate-200 bg-white p-3 text-left shadow-sm transition hover:border-sky-300 hover:bg-sky-50/40">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($action['icon'] === 'search')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                                        @elseif ($action['icon'] === 'calendar')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v3m8-3v3M3 9h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        @elseif ($action['icon'] === 'chart')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M7 16V8m5 8V5m5 11v-4"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16v10H4V7zm4 0V5h8v2"/>
                                        @endif
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $action['title'] }}</p>
                                    <p class="text-sm text-slate-600">{{ $action['text'] }}</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>

                <div class="ml-auto max-w-[90%] rounded-2xl rounded-tr-md bg-[#E9F2FF] px-4 py-3 text-sm text-slate-800 shadow-sm">
                    Montre-moi les consultations du jour
                    <div class="mt-1 text-right text-xs text-slate-400">10:45</div>
                </div>

                <div class="max-w-[92%] rounded-2xl rounded-tl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm">
                    <p class="mb-2 font-semibold">Voici les consultations du jour :</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>10:00 — Jean N. — Consultation générale</li>
                        <li>11:00 — Marie A. — Suivi de grossesse</li>
                        <li>12:00 — Paul B. — Consultation pédiatrique</li>
                        <li>14:30 — Sophie T. — Consultation générale</li>
                    </ul>
                    <div class="mt-2 text-right text-xs text-slate-400">10:45</div>
                </div>
            </div>

            <div class="shrink-0 border-t border-slate-200 bg-white p-3">
                <div class="flex items-center gap-2">
                    <input type="text" class="min-w-0 flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Écrivez votre message..." disabled>
                    <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-sky-500 text-white disabled:opacity-50" disabled>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <p class="mt-2 text-center text-xs text-slate-400">Vos données sont sécurisées et confidentielles.</p>
            </div>
        </div>
    </aside>
</div>
</template>

<button
    type="button"
    x-show="widgets['assistant_panel'] && !assistantOpen"
    x-cloak
    class="fixed z-40 flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-white shadow-lg"
    :class="isCompactScreen ? 'border-4 border-[#010f2e]' : 'border-[3px] border-[#010f2e]'"
    :style="isCompactScreen
        ? 'right: max(1rem, env(safe-area-inset-right)); bottom: max(1rem, env(safe-area-inset-bottom));'
        : 'right: 1.5rem; bottom: 1.5rem;'"
    @click="dismissWelcomeBubble(); assistantOpen = true"
    title="Ouvrir l’assistant"
>
    <img src="{{ asset('images/headbot.png') }}" alt="Assistant" class="h-full w-full object-cover">
</button>
<div
    x-show="widgets['assistant_panel'] && !assistantOpen && welcomeBubbleVisible && isCompactScreen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
    class="welcome-bubble-attention fixed z-40 max-w-[18rem] rounded-2xl border border-sky-100 bg-white px-3 py-2 text-sm text-slate-700 shadow-lg"
    style="right: max(1rem, env(safe-area-inset-right)); bottom: calc(max(1rem, env(safe-area-inset-bottom)) + 4.2rem);"
>
    <button type="button" class="absolute right-2 top-2 rounded p-0.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600" @click="dismissWelcomeBubble()" aria-label="Fermer la bulle">✕</button>
    <p x-text="currentNudgeMessage"></p>
    <span class="absolute -bottom-2 right-6 h-4 w-4 rotate-45 border-b border-r border-sky-100 bg-white"></span>
</div>
<div
    x-show="widgets['assistant_panel'] && !assistantOpen && welcomeBubbleVisible && !isCompactScreen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
    class="welcome-bubble-attention fixed z-40 max-w-xs rounded-2xl border border-sky-100 bg-white px-3 py-2 text-sm text-slate-700 shadow-lg"
    style="right: 1.5rem; bottom: 5.25rem;"
>
    <button type="button" class="absolute right-2 top-2 rounded p-0.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600" @click="dismissWelcomeBubble()" aria-label="Fermer la bulle">✕</button>
    <p x-text="currentNudgeMessage"></p>
    <span class="absolute -bottom-2 right-6 h-4 w-4 rotate-45 border-b border-r border-sky-100 bg-white"></span>
</div>

<template x-teleport="body">
<div
    x-show="widgets['assistant_panel'] && assistantOpen && isCompactScreen"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4"
    style="padding-left: max(1rem, env(safe-area-inset-left)); padding-right: max(1rem, env(safe-area-inset-right)); padding-bottom: max(1rem, env(safe-area-inset-bottom)); padding-top: max(1rem, env(safe-area-inset-top));"
    @keydown.escape.window="assistantOpen = false"
    @click.self="assistantOpen = false"
>
    <div
        @click.stop
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="flex max-h-[85vh] w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/80"
        style="width: min(32rem, calc(100vw - 2rem));"
    >
        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-4 py-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/headbot.png') }}" alt="Assistant" class="h-10 w-10 shrink-0 rounded-full object-cover">
                <div>
                    <p class="text-lg font-bold text-slate-900">CliniXora <span class="text-sky-600">Assistant</span></p>
                    <p class="text-xs text-slate-500"><span class="mr-1 inline-block h-2 w-2 rounded-full bg-emerald-500"></span>En ligne</p>
                </div>
            </div>
            <button type="button" class="rounded p-1.5 text-slate-500 hover:bg-slate-100" @click="assistantOpen = false" aria-label="Fermer l’assistant">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="min-h-0 flex flex-1 flex-col gap-3 overflow-y-auto bg-[#F6FAFF] p-4 text-sm">
            <div class="grid grid-cols-[minmax(0,80px)_1fr] gap-3 rounded-2xl bg-white p-3 shadow-sm sm:grid-cols-[80px_1fr]">
                <img src="{{ asset('images/bot.png') }}" alt="Bot" class="mx-auto h-12 w-12 rounded-xl object-cover sm:h-16 sm:w-16">
                <div class="rounded-xl border border-slate-200 bg-white p-3 text-slate-700">
                    <p class="font-semibold">Bonjour ! 👋</p>
                    <p>Je suis votre assistant <span class="font-semibold text-sky-600">CliniXora</span>. Comment puis-je vous aider aujourd’hui ?</p>
                </div>
            </div>

            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($quickActions as $action)
                    <button type="button" class="rounded-xl border border-slate-200 bg-white p-3 text-left shadow-sm transition hover:border-sky-300 hover:bg-sky-50/40">
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if ($action['icon'] === 'search')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                                    @elseif ($action['icon'] === 'calendar')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v3m8-3v3M3 9h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                    @elseif ($action['icon'] === 'chart')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M7 16V8m5 8V5m5 11v-4"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16v10H4V7zm4 0V5h8v2"/>
                                    @endif
                                </svg>
                            </span>
                            <span class="min-w-0 font-medium text-slate-800">{{ $action['title'] }}</span>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="ml-auto max-w-[90%] rounded-xl bg-[#E9F2FF] px-3 py-2 text-slate-800">Montre-moi les consultations du jour</div>
            <div class="max-w-[92%] rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-800">10:00 — Jean N. • 11:00 — Marie A. • 12:00 — Paul B.</div>
        </div>

        <div class="shrink-0 border-t border-slate-200 bg-white p-3">
            <div class="flex items-center gap-2">
                <input type="text" class="min-w-0 flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Écrivez votre message..." disabled>
                <button type="button" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white disabled:opacity-50" disabled>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
            <p class="mt-2 text-center text-xs text-slate-400">Vos données sont sécurisées et confidentielles.</p>
        </div>
    </div>
</div>
</template>
