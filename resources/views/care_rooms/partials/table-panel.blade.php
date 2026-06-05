<section class="flex min-h-[34rem] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="care-rooms-table-heading">
    <div class="shrink-0 border-b border-slate-100 px-4 py-4 sm:px-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 id="care-rooms-table-heading" class="text-base font-semibold text-slate-900">
                Liste des salles de soin ({{ $rooms->total() }})
            </h2>
            <form method="GET" action="{{ route('care_rooms.index') }}" class="relative w-full sm:max-w-xs" @submit.prevent="$dispatch('care-room-search', { param: 'search', value: $refs.headerSearch.value })">
                @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
                @if ($serviceFilter)<input type="hidden" name="service" value="{{ $serviceFilter }}">@endif
                @if ($perPage)<input type="hidden" name="per_page" value="{{ $perPage }}">@endif
                <label for="header_search" class="sr-only">Rechercher une salle de soin</label>
                <input id="header_search" x-ref="headerSearch" type="search" name="search" value="{{ $search }}" placeholder="Rechercher une salle de soin..." class="w-full rounded-lg border border-slate-200 py-2 pl-10 pr-3 text-sm focus:border-sky-400 focus:ring-sky-400" @input.debounce.350ms="$dispatch('care-room-search', { param: 'search', value: $event.target.value })">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
    </div>
    <div id="care-rooms-table-body" class="flex min-h-0 flex-1 flex-col px-4 pb-4 sm:px-6">
        @include('care_rooms.partials.table-body')
    </div>
</section>
