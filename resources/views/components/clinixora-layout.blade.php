@props([
    'title' => 'Tableau de bord',
    'customizeWidgets' => [],
    'assistantMessages' => [],
])

@php
    $widgetsInitial = collect($customizeWidgets)->mapWithKeys(fn ($w) => [$w['id'] => $w['default']])->all();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'Clinixora') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body
    class="min-h-screen overflow-x-hidden bg-[#F8FAFC] text-slate-900 antialiased"
    x-data="{
        widgetDefs: {{ \Illuminate\Support\Js::from($customizeWidgets) }},
        widgets: {{ \Illuminate\Support\Js::from($widgetsInitial) }},
        assistantOpen: true,
        assistantModalOpen: false,
        customizeOpen: false,
        modulesPopover: false,
        mobileNavOpen: false,
        desktopSidebarCollapsed: false,
        isCompactScreen: false,
        updateViewportFlags() {
            this.isCompactScreen = window.innerWidth < 1024;
            if (!this.isCompactScreen) {
                this.assistantModalOpen = false;
                this.mobileNavOpen = false;
            }
        },
        init() {
            const saved = window.localStorage.getItem('clinixora.sidebar.collapsed');
            this.desktopSidebarCollapsed = saved === '1';
            this.updateViewportFlags();
            window.addEventListener('resize', () => this.updateViewportFlags());
        },
        toggleSidebar() {
            this.desktopSidebarCollapsed = !this.desktopSidebarCollapsed;
            window.localStorage.setItem('clinixora.sidebar.collapsed', this.desktopSidebarCollapsed ? '1' : '0');
        },
        showAll() {
            this.widgetDefs.forEach(w => { this.widgets[w.id] = true });
        },
        resetWidgets() {
            this.widgetDefs.forEach(w => { this.widgets[w.id] = w.default });
        },
    }"
    x-init="init()"
>
    <div class="flex min-h-screen w-full flex-col overflow-x-hidden sm:flex-row">
        <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:hidden">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                @click="mobileNavOpen = true"
                aria-label="Ouvrir le menu"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Menu
            </button>
            <div class="truncate text-sm font-semibold text-slate-900">{{ $title }}</div>
            <button
                type="button"
                class="inline-flex items-center rounded-lg bg-sky-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-sky-600"
                @click="customizeOpen = true"
            >
                Widgets
            </button>
        </header>

        <div x-show="mobileNavOpen" x-cloak class="fixed inset-0 z-40 bg-slate-900/50 sm:hidden" @click="mobileNavOpen = false"></div>
        <div
            x-show="mobileNavOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-[85vw] max-w-sm transform overflow-y-auto sm:hidden"
        >
            <div class="flex h-full flex-col bg-[#0A192F]">
                <div class="flex justify-end p-3">
                    <button type="button" class="rounded p-2 text-slate-300 hover:bg-white/10 hover:text-white" @click="mobileNavOpen = false" aria-label="Fermer le menu">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="min-h-0 flex-1">
                    @include('partials.clinixora-sidebar')
                </div>
            </div>
        </div>

        <div class="hidden sm:flex">
            @include('partials.clinixora-sidebar')
        </div>

        <div class="flex min-h-0 min-w-0 flex-1 flex-col sm:flex-row">
            <div class="min-h-0 min-w-0 flex-1">
                {{ $slot }}
            </div>

            @include('partials.clinixora-assistant', ['assistantMessages' => $assistantMessages])
        </div>
    </div>

    <div
        x-show="customizeOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
        @keydown.escape.window="customizeOpen = false"
    >
        <div
            @click.outside="customizeOpen = false"
            class="max-h-[85vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Personnaliser le tableau de bord</h2>
                    <p class="mt-1 text-sm text-slate-500">Activez ou masquez les blocs (valeurs génériques — persistance utilisateur à brancher, voir docs/DASHBOARD_GRAPHIQUES.md).</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" @click="customizeOpen = false" aria-label="Fermer">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <ul class="mt-6 space-y-3">
                <template x-for="w in widgetDefs" :key="w.id">
                    <li class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5">
                        <span class="text-sm font-medium text-slate-700" x-text="w.label"></span>
                        <button
                            type="button"
                            class="relative h-6 w-11 shrink-0 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                            :class="widgets[w.id] ? 'bg-sky-500' : 'bg-slate-200'"
                            @click="widgets[w.id] = !widgets[w.id]"
                            role="switch"
                            :aria-checked="widgets[w.id]"
                        >
                            <span class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform" :class="widgets[w.id] ? 'left-5' : 'left-0.5'"></span>
                        </button>
                    </li>
                </template>
            </ul>
            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50" @click="resetWidgets(); customizeOpen = false">Réinitialiser</button>
                <button type="button" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600" @click="customizeOpen = false">Enregistrer</button>
            </div>
        </div>
    </div>
</body>
</html>
