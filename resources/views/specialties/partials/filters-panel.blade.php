<aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="specialties-filters-heading">
    <h2 id="specialties-filters-heading" class="text-sm font-semibold text-slate-900">Filtres</h2>

    <form method="GET" action="{{ route('specialties.index') }}" class="mt-4 space-y-4">
        <div>
            <label for="filter_search" class="block text-xs font-medium text-slate-600">Rechercher</label>
            <input
                id="filter_search"
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Rechercher..."
                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400"
            >
        </div>

        <div>
            <label for="filter_status" class="block text-xs font-medium text-slate-600">Statut</label>
            <select id="filter_status" name="status" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
                <option value="" @selected($status === '')>Tous</option>
                <option value="active" @selected($status === 'active')>Actif</option>
                <option value="inactive" @selected($status === 'inactive')>Inactif</option>
            </select>
        </div>

        @if ($perPage)
            <input type="hidden" name="per_page" value="{{ $perPage }}">
        @endif

        <div class="flex gap-2 pt-2">
            <a
                href="{{ route('specialties.index', ['per_page' => $perPage]) }}"
                class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Réinitialiser
            </a>
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg bg-sky-500 px-3 py-2 text-xs font-medium text-white hover:bg-sky-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Appliquer
            </button>
        </div>
    </form>
</aside>
