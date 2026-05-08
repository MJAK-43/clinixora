{{-- Assistant — zone décrite dans docs/DESIGN_SYSTEM.md ; contenu générique. --}}
<aside
    x-show="widgets['assistant_panel'] && assistantOpen && !isCompactScreen"
    x-cloak
    class="relative flex w-80 shrink-0 flex-col border-l border-slate-200 bg-white shadow-sm"
>
    <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-[#0A192F] px-4 py-3 text-white">
        <div class="flex min-w-0 items-center gap-2">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-500/20 text-sky-300">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l1.09 3.26L16 5l-3.26 1.09L12 9.18 10.26 6.09 7 5l3.91-.74L12 2zm0 8.82l1.09 3.26L16 12l-3.26 1.09L12 16.36 10.26 13.27 7 12l3.91-1.09L12 10.82z"/></svg>
            </span>
            <div class="min-w-0">
                <div class="truncate text-sm font-semibold">Assistant Clinixora</div>
                <div class="text-xs text-slate-400">Résumés & exports</div>
            </div>
        </div>
        <button type="button" class="shrink-0 rounded p-1.5 text-slate-400 hover:bg-white/10 hover:text-white" title="Réduire" @click="assistantOpen = false">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
        </button>
    </div>

    <div class="flex flex-1 flex-col gap-3 overflow-y-auto p-4 text-sm">
        @foreach ($assistantMessages ?? [] as $msg)
            <div class="rounded-xl rounded-tl-sm bg-slate-100 px-3 py-2.5 text-slate-800 shadow-sm">
                @isset($msg['html'])
                    <div class="leading-relaxed">{!! $msg['html'] !!}</div>
                @endisset
                @isset($msg['attachment'])
                    <a href="#" class="mt-2 flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-sky-600 hover:bg-slate-50" onclick="return false;">
                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="truncate">{{ $msg['attachment']['name'] }}</span>
                        <span class="ml-auto text-slate-400">{{ $msg['attachment']['size'] }}</span>
                    </a>
                @endisset
            </div>
        @endforeach
    </div>

    <div class="border-t border-slate-200 p-3">
        <div class="flex gap-2">
            <button type="button" class="rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-50" title="Pièce jointe" aria-label="Pièce jointe">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </button>
            <input type="text" class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Écrivez votre message…" disabled aria-disabled="true">
            <button type="button" class="rounded-lg bg-sky-500 px-3 py-2 text-white hover:bg-sky-600 disabled:opacity-50" disabled title="Bientôt disponible">Envoyer</button>
        </div>
        <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-400">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Les actions respectent vos droits
        </p>
    </div>
</aside>

<button
    type="button"
    x-show="widgets['assistant_panel'] && isCompactScreen"
    x-cloak
    class="fixed z-40 flex h-12 w-12 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg hover:bg-sky-600"
    style="right: max(1rem, env(safe-area-inset-right)); bottom: max(1rem, env(safe-area-inset-bottom));"
    @click="assistantModalOpen = true"
    title="Ouvrir l’assistant"
>
    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.09 3.26L16 5l-3.26 1.09L12 9.18 10.26 6.09 7 5l3.91-.74L12 2z"/></svg>
</button>

<button
    type="button"
    x-show="widgets['assistant_panel'] && !assistantOpen && !isCompactScreen"
    x-cloak
    class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg hover:bg-sky-600"
    @click="assistantOpen = true"
    title="Ouvrir l’assistant"
>
    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.09 3.26L16 5l-3.26 1.09L12 9.18 10.26 6.09 7 5l3.91-.74L12 2z"/></svg>
</button>

<div
    x-show="widgets['assistant_panel'] && assistantModalOpen && isCompactScreen"
    x-cloak
    class="fixed inset-0 z-50 flex justify-end bg-slate-900/60"
    @keydown.escape.window="assistantModalOpen = false"
>
    <div
        @click.outside="assistantModalOpen = false"
        x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="flex h-full w-[88vw] max-w-sm flex-col overflow-hidden bg-white shadow-2xl"
    >
        <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-[#0A192F] px-4 py-3 text-white">
            <div class="flex min-w-0 items-center gap-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-500/20 text-sky-300">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l1.09 3.26L16 5l-3.26 1.09L12 9.18 10.26 6.09 7 5l3.91-.74L12 2zm0 8.82l1.09 3.26L16 12l-3.26 1.09L12 16.36 10.26 13.27 7 12l3.91-1.09L12 10.82z"/></svg>
                </span>
                <div class="min-w-0">
                    <div class="truncate text-sm font-semibold">Assistant Clinixora</div>
                    <div class="text-xs text-slate-400">Résumés & exports</div>
                </div>
            </div>
            <button type="button" class="rounded p-1.5 text-slate-300 hover:bg-white/10 hover:text-white" @click="assistantModalOpen = false" aria-label="Fermer l’assistant">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex flex-1 flex-col gap-3 overflow-y-auto p-4 text-sm">
            @foreach ($assistantMessages ?? [] as $msg)
                <div class="rounded-xl rounded-tl-sm bg-slate-100 px-3 py-2.5 text-slate-800 shadow-sm">
                    @isset($msg['html'])
                        <div class="leading-relaxed">{!! $msg['html'] !!}</div>
                    @endisset
                    @isset($msg['attachment'])
                        <a href="#" class="mt-2 flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-sky-600 hover:bg-slate-50" onclick="return false;">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="truncate">{{ $msg['attachment']['name'] }}</span>
                            <span class="ml-auto text-slate-400">{{ $msg['attachment']['size'] }}</span>
                        </a>
                    @endisset
                </div>
            @endforeach
        </div>

        <div class="border-t border-slate-200 p-3">
            <div class="flex gap-2">
                <button type="button" class="rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-50" title="Pièce jointe" aria-label="Pièce jointe">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </button>
                <input type="text" class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Écrivez votre message…" disabled aria-disabled="true">
                <button type="button" class="rounded-lg bg-sky-500 px-3 py-2 text-white hover:bg-sky-600 disabled:opacity-50" disabled title="Bientôt disponible">Envoyer</button>
            </div>
            <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Les actions respectent vos droits
            </p>
        </div>
    </div>
</div>
