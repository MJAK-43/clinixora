{{-- Menu métier — aligné maquette + docs/MENU_METIER_REFERENCE.md (navigation métier Clinixora). --}}
@php
    $navItems = [
        ['route' => 'dashboard', 'href' => null, 'label' => 'Tableau de bord', 'icon' => 'home', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Agenda', 'icon' => 'calendar', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Patients', 'icon' => 'users', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Consultations', 'icon' => 'clipboard', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Dossiers médicaux', 'icon' => 'folder', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Facturation', 'icon' => 'currency', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Encaissements', 'icon' => 'banknotes', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Laboratoire', 'icon' => 'beaker', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Pharmacie', 'icon' => 'pill', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Stock', 'icon' => 'cube', 'badge' => null],
        ['route' => null, 'href' => '#', 'label' => 'Messagerie', 'icon' => 'chat', 'badge' => 3],
        ['route' => null, 'href' => '#', 'label' => 'Rapports', 'icon' => 'chart', 'badge' => null],
        ['route' => 'parametres', 'href' => null, 'label' => 'Paramètres', 'icon' => 'cog', 'badge' => null],
    ];
@endphp

<aside
    class="flex h-full w-full shrink-0 flex-col border-[#15327a] bg-[#010f2e] text-slate-200 transition-all duration-200 sm:border-r"
    :class="desktopSidebarCollapsed ? 'sm:w-20' : 'sm:w-64'"
    style="background-color: #010f2e;"
>
    <div class="relative flex items-center justify-center px-4 py-5" :class="desktopSidebarCollapsed ? 'sm:px-2' : ''">
        <img
            src="{{ asset('images/logo_blue.png') }}"
            alt="CliniXora"
            class="h-20 w-auto max-w-[11rem] shrink-0 rounded-xl p-1.5 object-contain sm:h-[5.25rem] sm:max-w-[12rem]"
            :class="desktopSidebarCollapsed ? 'sm:h-12 sm:w-12 sm:max-w-[3rem] sm:p-1' : ''"
        >
        <button
            type="button"
            class="absolute right-3 hidden rounded p-1.5 text-slate-400 hover:bg-white/10 hover:text-white sm:inline-flex"
            :title="desktopSidebarCollapsed ? 'Déplier le menu' : 'Replier le menu'"
            @click="toggleSidebar()"
            :aria-label="desktopSidebarCollapsed ? 'Déplier le menu latéral' : 'Replier le menu latéral'"
        >
            <svg x-show="!desktopSidebarCollapsed" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            <svg x-show="desktopSidebarCollapsed" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </button>
    </div>

    <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 pb-4" aria-label="Navigation principale">
        @foreach ($navItems as $item)
            @php
                $isActive = $item['route'] && request()->routeIs($item['route']);
                $url = $item['route'] ? route($item['route']) : $item['href'];
            @endphp
            <a
                href="{{ $url }}"
                class="group flex items-center gap-3 rounded-lg border-l-4 py-2.5 pl-2 pr-3 text-sm font-medium transition-colors {{ $isActive ? 'border-sky-400 bg-sky-500/15 text-white' : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white' }}"
                @click="if (window.innerWidth < 640) mobileNavOpen = false"
            >
                @include('partials.clinixora-nav-icon', ['icon' => $item['icon']])
                <span class="flex-1 truncate" :class="desktopSidebarCollapsed ? 'sm:hidden' : ''">{{ $item['label'] }}</span>
                @if ($item['badge'])
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-sky-500 px-1.5 text-xs font-semibold text-white" :class="desktopSidebarCollapsed ? 'sm:hidden' : ''">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Modules affichés (maquette) — même logique que Personnaliser — docs/DESIGN_SYSTEM.md --}}
    <div class="relative border-t border-slate-700/60 px-3 py-3" :class="desktopSidebarCollapsed ? 'sm:hidden' : ''">
        <button
            type="button"
            class="flex w-full items-center gap-2 rounded-lg border border-slate-600/80 bg-slate-800/50 px-3 py-2 text-left text-xs font-medium text-slate-300 transition hover:bg-slate-800"
            @click="modulesPopover = !modulesPopover"
        >
            <svg class="h-4 w-4 shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Modules affichés</span>
        </button>
        <div
            x-show="modulesPopover"
            x-cloak
            x-transition
            @click.outside="modulesPopover = false"
            class="absolute bottom-full left-3 right-3 mb-2 max-h-64 overflow-y-auto rounded-lg border border-slate-600 bg-white p-3 text-slate-800 shadow-xl"
        >
            <p class="mb-2 text-xs font-medium text-slate-500">Widgets du tableau de bord</p>
            <ul class="max-h-40 space-y-2 overflow-y-auto text-sm">
                <template x-for="w in widgetDefs" :key="w.id">
                    <li class="flex items-center gap-2">
                        <input type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" x-model="widgets[w.id]">
                        <span x-text="w.label" class="text-slate-700"></span>
                    </li>
                </template>
            </ul>
            <div class="mt-3 flex gap-2 border-t border-slate-100 pt-3">
                <button type="button" class="flex-1 rounded border border-slate-200 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50" @click="resetWidgets()">Réinitialiser</button>
                <button type="button" class="flex-1 rounded bg-sky-500 py-1.5 text-xs font-medium text-white hover:bg-sky-600" @click="modulesPopover = false">Enregistrer</button>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-700/60 p-4" :class="desktopSidebarCollapsed ? 'sm:hidden' : ''">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold text-white">
                {{ strtoupper(Str::substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                <div class="truncate text-xs text-slate-400">{{ auth()->user()->role?->name ?? 'Utilisateur' }}</div>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <a href="{{ route('profile.edit') }}" class="flex-1 rounded-lg border border-slate-600 py-2 text-center text-xs font-medium text-slate-300 hover:bg-white/5">Profil</a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-slate-800 py-2 text-xs font-medium text-slate-200 hover:bg-slate-700">Déconnexion</button>
            </form>
        </div>
    </div>
</aside>
