@php
    $agentRoutes = [
        'catalog' => route('agent.catalog'),
        'messages' => route('agent.messages.store'),
        'confirm' => route('agent.confirm'),
    ];
@endphp

<div
    x-data="clinixoraAssistantChat(@js($agentRoutes))"
    x-init="init()"
    class="flex min-h-0 flex-1 flex-col bg-[#F6FAFF]"
>
        {{-- Catalogue toujours visible (ne défile pas avec l'historique) --}}
        <section
            x-ref="explorer"
            class="shrink-0 border-b border-slate-200/90 bg-white p-2.5 shadow-sm"
        >
            <div
                x-ref="explorerPanel"
                class="max-h-[min(14rem,38vh)] overflow-y-auto"
            >
            <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                <div class="flex min-w-0 items-center gap-1.5">
                    <button
                        type="button"
                        x-show="explorerStep !== 'modules'"
                        x-cloak
                        class="shrink-0 rounded-md p-1 text-slate-500 hover:bg-slate-100"
                        @click="explorerBack()"
                        aria-label="Retour"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <p class="truncate text-xs font-semibold text-slate-800" x-text="breadcrumbLabel"></p>
                </div>
                <button
                    type="button"
                    x-show="explorerStep !== 'modules'"
                    x-cloak
                    class="shrink-0 text-[11px] font-medium text-sky-600 hover:text-sky-700"
                    @click="resetExplorer()"
                >
                    Modules
                </button>
            </div>

            <div x-show="catalogLoading" class="py-4 text-center text-xs text-slate-500">Chargement…</div>
            <div x-show="catalogError && !catalogLoading" x-cloak class="py-2 text-xs text-red-600" x-text="catalogError"></div>
            <div x-show="!catalogLoading && !catalogError && catalog.length === 0" x-cloak class="py-2 text-xs text-slate-500">
                Aucune action pour votre rôle.
            </div>

            <div x-show="!catalogLoading && explorerStep === 'modules' && catalog.length > 0" class="mt-2 grid gap-1.5 sm:grid-cols-2">
                <template x-for="module in catalog" :key="module.key">
                    <button
                        type="button"
                        class="rounded-lg border border-slate-200 px-2.5 py-2 text-left transition hover:border-sky-300 hover:bg-sky-50/60"
                        @click="openModule(module)"
                    >
                        <p class="text-xs font-semibold text-slate-900" x-text="module.label"></p>
                        <p class="mt-0.5 line-clamp-1 text-[10px] text-slate-500" x-text="module.description"></p>
                        <p class="mt-1 text-[10px] font-medium text-sky-600"><span x-text="module.actions.length"></span> action(s)</p>
                    </button>
                </template>
            </div>

            <div x-show="!catalogLoading && explorerStep === 'actions' && selectedModule" class="mt-2 space-y-1">
                <template x-for="action in selectedModule?.actions ?? []" :key="action.action_key">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 px-2.5 py-2 text-left text-xs transition hover:border-sky-300 hover:bg-sky-50/60"
                        @click="openAction(action)"
                    >
                        <span class="min-w-0">
                            <span class="block font-medium text-slate-900" x-text="action.label"></span>
                            <span class="line-clamp-1 text-[10px] text-slate-500" x-text="action.description"></span>
                        </span>
                        <span x-show="action.requires_confirmation" x-cloak class="shrink-0 rounded bg-amber-100 px-1.5 py-0.5 text-[9px] text-amber-800">conf.</span>
                    </button>
                </template>
            </div>

            <div x-show="!catalogLoading && explorerStep === 'syntax' && selectedAction" class="mt-2 space-y-2">
                <p class="text-[11px] text-slate-600">Phrase à saisir (adaptez les valeurs) :</p>
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2">
                    <p class="font-mono text-[11px] leading-snug text-slate-800" x-text="selectedAction?.syntax"></p>
                </div>
                <button
                    type="button"
                    class="w-full rounded-lg bg-sky-500 py-2 text-xs font-semibold text-white hover:bg-sky-600"
                    @click="useSyntax()"
                >
                    Insérer dans le champ de saisie
                </button>
            </div>
            </div>
        </section>

        {{-- Historique du chat (défilement indépendant) --}}
        <div x-ref="messagesScroll" class="min-h-0 flex-1 space-y-2 overflow-y-auto p-3">
        <template x-for="(msg, index) in messages" :key="index">
            <div
                :class="msg.role === 'user'
                    ? 'ml-auto max-w-[88%] rounded-xl rounded-br-sm bg-sky-500 px-2.5 py-1.5 text-[11px] leading-snug text-white shadow-sm'
                    : 'max-w-[92%] rounded-xl rounded-bl-sm border border-slate-200/80 bg-white px-2.5 py-1.5 text-[11px] leading-snug text-slate-700 shadow-sm'"
            >
                <template x-if="msg.display?.type === 'list'">
                    <div>
                        <p class="font-medium text-slate-900" x-text="msg.display.title"></p>
                        <p class="mt-0.5 text-[10px] text-slate-500">
                            <span x-text="msg.display.total"></span> élément(s)
                        </p>
                        <ul class="mt-1.5 grid grid-cols-2 gap-x-2 gap-y-0.5">
                            <template x-for="(item, i) in visibleListItems(msg)" :key="i">
                                <li class="truncate text-[10px] text-slate-600" x-text="item"></li>
                            </template>
                        </ul>
                        <button
                            type="button"
                            x-show="hasMoreListItems(msg)"
                            x-cloak
                            class="mt-1.5 text-[10px] font-medium text-sky-600 hover:text-sky-700"
                            @click="msg.expanded = true"
                        >
                            Voir plus (<span x-text="hiddenListCount(msg)"></span>)
                        </button>
                        <button
                            type="button"
                            x-show="msg.expanded && msg.display.items.length > (msg.display.preview_limit ?? 6)"
                            x-cloak
                            class="mt-1.5 text-[10px] font-medium text-slate-500 hover:text-slate-700"
                            @click="msg.expanded = false"
                        >
                            Voir moins
                        </button>
                    </div>
                </template>
                <template x-if="!msg.display?.type">
                    <p class="whitespace-pre-wrap" x-text="msg.content"></p>
                </template>
                <div class="mt-0.5 text-right text-[9px] opacity-60" x-text="msg.time"></div>
            </div>
        </template>

        <div x-show="loading" x-cloak class="text-[11px] text-slate-500">En cours…</div>

        <div x-show="pending" x-cloak class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-2 text-[11px] text-amber-900">
            <p class="font-medium">Confirmation</p>
            <p class="mt-0.5" x-text="pending?.summary"></p>
            <div class="mt-2 flex gap-1.5">
                <button type="button" class="rounded-md bg-sky-500 px-2.5 py-1 text-[10px] font-semibold text-white" @click="confirmPending()" :disabled="loading">Confirmer</button>
                <button type="button" class="rounded-md border border-slate-300 bg-white px-2.5 py-1 text-[10px] text-slate-700" @click="cancelPending()" :disabled="loading">Annuler</button>
            </div>
        </div>
    </div>

    <div class="shrink-0 border-t border-slate-200 bg-white p-2.5">
        <form class="flex items-center gap-1.5" @submit.prevent="send()">
            <input
                x-ref="messageInput"
                type="text"
                x-model="draft"
                class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-xs placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                placeholder="Votre message…"
                :disabled="loading"
                autocomplete="off"
            >
            <button
                type="submit"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white hover:bg-sky-600 disabled:opacity-50"
                :disabled="loading || !draft.trim()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </form>
    </div>
</div>
