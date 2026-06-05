@php
    $deleteRedirectQuery = collect(request()->query())
        ->except(['district', 'edit_district', 'create_district', 'edit_city', 'create_city'])
        ->all();
@endphp

@if (! $selectedCity)
    <div class="flex flex-1 items-center justify-center px-4 py-12 text-center text-sm text-slate-500">
        @if (! $selectedCountry)
            Sélectionnez un pays, puis une ville.
        @else
            Sélectionnez une ville pour afficher ses quartiers.
        @endif
    </div>
@else
    <div class="geo-panel__body min-h-0 flex-1 overflow-auto">
        <table class="min-w-full text-sm">
            <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
                <tr>
                    <th class="px-4 py-2.5">Quartier</th>
                    <th class="px-4 py-2.5">Code</th>
                    <th class="w-10 px-2 py-2.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($districts as $district)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2.5 font-medium text-slate-900">{{ $district->name }}</td>
                        <td class="px-4 py-2.5 text-slate-600">{{ $district->code }}</td>
                        <td class="px-2 py-2.5 text-right">
                            @include('geography.partials.row-actions', [
                                'editUrl' => route('geography.countries.index', array_merge(request()->query(), ['edit_district' => $district->id])),
                                'deleteAction' => route('geography.districts.destroy', $district),
                                'deleteConfirm' => 'Supprimer le quartier « '.$district->name.' » ?',
                                'redirectQuery' => $deleteRedirectQuery,
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">Aucun quartier pour cette ville.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<div class="shrink-0 px-4 pb-4">
    @include('geography.partials.pagination', ['paginator' => $districts, 'label' => 'quartiers'])
</div>
