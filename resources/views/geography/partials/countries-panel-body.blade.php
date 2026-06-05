@php
    $selectUrl = fn (int $countryId) => route('geography.countries.index', array_merge(
        request()->only(['country_search', 'countries_page']),
        ['country' => $countryId, 'city' => null]
    ));
    $deleteRedirectQuery = collect(request()->query())
        ->except(['country', 'city', 'edit_country', 'create_country'])
        ->all();
@endphp

<div class="geo-panel__body min-h-0 flex-1 overflow-auto">
    <table class="min-w-full text-sm">
        <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
            <tr>
                <th class="px-4 py-2.5">Pays</th>
                <th class="px-4 py-2.5">Code</th>
                <th class="w-10 px-2 py-2.5"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($countries as $country)
                @php $isSelected = $selectedCountry?->id === $country->id; @endphp
                <tr @class(['transition', 'bg-sky-50' => $isSelected, 'hover:bg-slate-50' => ! $isSelected])>
                    <td class="px-4 py-2.5">
                        <a href="{{ $selectUrl($country->id) }}" data-geo-nav class="flex items-center gap-2 font-medium text-slate-900">
                            <img
                                src="https://flagcdn.com/w20/{{ strtolower($country->flag_code ?? $country->code) }}.png"
                                alt=""
                                class="h-3 w-5 rounded-sm object-cover"
                                loading="lazy"
                            >
                            {{ $country->name }}
                        </a>
                    </td>
                    <td class="px-4 py-2.5 text-slate-600">{{ $country->code }}</td>
                    <td class="px-2 py-2.5 text-right">
                        @include('geography.partials.row-actions', [
                            'editUrl' => route('geography.countries.index', array_merge(request()->query(), ['edit_country' => $country->id])),
                            'deleteAction' => route('geography.countries.destroy', $country),
                            'deleteConfirm' => 'Supprimer le pays « '.$country->name.' » ?',
                            'redirectQuery' => $deleteRedirectQuery,
                        ])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-slate-500">Aucun pays trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="shrink-0 px-4 pb-4">
    @include('geography.partials.pagination', ['paginator' => $countries, 'label' => 'pays'])
</div>
