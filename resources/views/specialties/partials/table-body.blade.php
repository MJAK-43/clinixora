@php
    $deleteRedirectQuery = collect(request()->query())
        ->except(['edit_specialty', 'create_specialty'])
        ->all();
@endphp

<div class="min-h-0 flex-1 overflow-auto">
    <table class="min-w-full text-sm">
        <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
            <tr>
                <th class="w-12 px-4 py-3"><span class="sr-only">Icône</span></th>
                <th class="px-4 py-3">Spécialité</th>
                <th class="px-4 py-3">Code</th>
                <th class="hidden px-4 py-3 md:table-cell">Description</th>
                <th class="px-4 py-3">Statut</th>
                <th class="w-24 px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($specialties as $specialty)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50">
                            @include('specialties.partials.icon', ['icon' => $specialty->icon])
                        </span>
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $specialty->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $specialty->code }}</td>
                    <td class="hidden max-w-xs truncate px-4 py-3 text-slate-500 md:table-cell">{{ $specialty->description }}</td>
                    <td class="px-4 py-3">
                        @if ($specialty->is_active)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Actif</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Inactif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @include('specialties.partials.row-actions', [
                            'editUrl' => route('specialties.index', array_merge(request()->query(), ['edit_specialty' => $specialty->id])),
                            'deleteAction' => route('specialties.destroy', $specialty),
                            'deleteConfirm' => 'Supprimer la spécialité « '.$specialty->name.' » ?',
                            'redirectQuery' => $deleteRedirectQuery,
                        ])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">Aucune spécialité trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('specialties.partials.pagination', [
    'paginator' => $specialties,
    'label' => 'spécialités',
    'perPage' => $perPage,
])
