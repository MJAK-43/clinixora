@php
    $baseQuery = request()->only(['country', 'country_search', 'countries_page', 'city_search', 'cities_page', 'district_search', 'districts_page']);
    $selectUrl = fn (int $cityId) => route('geography.countries.index', array_merge($baseQuery, ['city' => $cityId]));
    $deleteRedirectQuery = collect(request()->query())
        ->except(['city', 'edit_city', 'create_city', 'edit_district', 'create_district'])
        ->all();
@endphp

@if (! $selectedCountry)
    <div class="flex flex-1 items-center justify-center px-4 py-12 text-center text-sm text-slate-500">
        Sélectionnez un pays pour afficher ses villes.
    </div>
@else
    <div class="geo-panel__body min-h-0 flex-1 overflow-auto">
        <table class="min-w-full text-sm">
            <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
                <tr>
                    <th class="px-4 py-2.5">Ville</th>
                    <th class="px-4 py-2.5">Code</th>
                    <th class="w-10 px-2 py-2.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($cities as $city)
                    @php $isSelected = $selectedCity?->id === $city->id; @endphp
                    <tr @class(['transition', 'bg-sky-50' => $isSelected, 'hover:bg-slate-50' => ! $isSelected])>
                        <td class="px-4 py-2.5">
                            <a href="{{ $selectUrl($city->id) }}" data-geo-nav class="font-medium text-slate-900">{{ $city->name }}</a>
                        </td>
                        <td class="px-4 py-2.5 text-slate-600">{{ $city->code }}</td>
                        <td class="px-2 py-2.5 text-right">
                            @include('geography.partials.row-actions', [
                                'editUrl' => route('geography.countries.index', array_merge(request()->query(), ['edit_city' => $city->id])),
                                'deleteAction' => route('geography.cities.destroy', $city),
                                'deleteConfirm' => 'Supprimer la ville « '.$city->name.' » et tous ses quartiers ?',
                                'redirectQuery' => $deleteRedirectQuery,
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">Aucune ville pour ce pays.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<div class="shrink-0 px-4 pb-4">
    @include('geography.partials.pagination', ['paginator' => $cities, 'label' => 'villes'])
</div>
