<section class="geo-panel flex h-full min-h-[34rem] max-h-[calc(100vh-10.5rem)] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="districts-panel-heading">
    <div class="shrink-0 border-b border-slate-100 px-4 py-4">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <h2 id="districts-panel-heading" class="text-sm font-semibold text-slate-900">Quartiers de la ville</h2>
                @if ($selectedCity)
                    <p class="mt-0.5 truncate text-xs text-slate-500">{{ $selectedCity->name }}</p>
                @endif
            </div>
            @if ($selectedCity)
                <a
                    href="{{ route('geography.countries.index', array_merge(request()->query(), ['create_district' => 1])) }}"
                    class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-sky-500 px-2.5 py-1.5 text-xs font-medium text-white hover:bg-sky-600"
                >
                    <span aria-hidden="true">+</span> Ajouter un quartier
                </a>
            @endif
        </div>
        @if ($selectedCity)
            @include('geography.partials.live-search-form', [
                'panel' => 'districts',
                'searchName' => 'district_search',
                'searchValue' => $districtSearch,
                'placeholder' => 'Rechercher un quartier...',
            ])
        @endif
    </div>

    <div id="geo-districts-body" class="flex min-h-0 flex-1 flex-col" @click="onPanelClick($event, 'districts')">
        @include('geography.partials.districts-panel-body')
    </div>
</section>
