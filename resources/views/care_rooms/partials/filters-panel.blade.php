<aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="care-rooms-filters-heading">
    <h2 id="care-rooms-filters-heading" class="text-sm font-semibold text-slate-900">Filtres</h2>

    <form method="GET" action="{{ route('care_rooms.index') }}" class="mt-4 space-y-4">
        <div>
            <label for="filter_search" class="block text-xs font-medium text-slate-600">Rechercher</label>
            <input id="filter_search" type="search" name="search" value="{{ $search }}" placeholder="Rechercher..." class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
        </div>
        <div>
            <label for="filter_status" class="block text-xs font-medium text-slate-600">Statut</label>
            <select id="filter_status" name="status" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
                <option value="" @selected($status === '')>Tous</option>
                <option value="active" @selected($status === 'active')>Actif</option>
                <option value="inactive" @selected($status === 'inactive')>Inactif</option>
            </select>
        </div>
        <div>
            <label for="filter_service" class="block text-xs font-medium text-slate-600">Services</label>
            <select id="filter_service" name="service" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
                <option value="" @selected($serviceFilter === '')>Tous les services</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected((string) $serviceFilter === (string) $service->id)>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        @if ($perPage)
            <input type="hidden" name="per_page" value="{{ $perPage }}">
        @endif
        <div class="flex gap-2 pt-2">
            <a href="{{ route('care_rooms.index', ['per_page' => $perPage]) }}" class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">Réinitialiser</a>
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg bg-sky-500 px-3 py-2 text-xs font-medium text-white hover:bg-sky-600">Appliquer</button>
        </div>
    </form>
</aside>
