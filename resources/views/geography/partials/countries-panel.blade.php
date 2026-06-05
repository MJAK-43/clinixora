<section class="geo-panel flex h-full min-h-[34rem] max-h-[calc(100vh-10.5rem)] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="countries-panel-heading">
    <div class="shrink-0 border-b border-slate-100 px-4 py-4">
        <h2 id="countries-panel-heading" class="text-sm font-semibold text-slate-900">Pays</h2>
        @include('geography.partials.live-search-form', [
            'panel' => 'countries',
            'searchName' => 'country_search',
            'searchValue' => $countrySearch,
            'placeholder' => 'Rechercher un pays...',
        ])
    </div>

    <div id="geo-countries-body" class="flex min-h-0 flex-1 flex-col" @click="onPanelClick($event, 'countries')">
        @include('geography.partials.countries-panel-body')
    </div>
</section>
