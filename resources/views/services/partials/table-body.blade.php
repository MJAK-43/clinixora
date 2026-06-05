@php
    $deleteRedirectQuery = collect(request()->query())
        ->except(['edit_service', 'create_service'])
        ->all();
@endphp

<div class="min-h-0 flex-1 overflow-auto">
    <table class="min-w-full text-sm">
        <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
            <tr>
                <th class="w-12 px-4 py-3"><span class="sr-only">Icône</span></th>
                <th class="px-4 py-3">Service</th>
                <th class="px-4 py-3">Code</th>
                <th class="hidden px-4 py-3 md:table-cell">Description</th>
                <th class="px-4 py-3">Statut</th>
                <th class="w-24 px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($services as $service)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50">
                            @include('services.partials.icon', ['icon' => $service->icon])
                        </span>
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $service->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $service->code }}</td>
                    <td class="hidden max-w-xs truncate px-4 py-3 text-slate-500 md:table-cell">{{ $service->description }}</td>
                    <td class="px-4 py-3">
                        @if ($service->is_active)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Actif</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Inactif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @include('services.partials.row-actions', [
                            'editUrl' => route('services.index', array_merge(request()->query(), ['edit_service' => $service->id])),
                            'deleteAction' => route('services.destroy', $service),
                            'deleteConfirm' => 'Supprimer le service « '.$service->name.' » ?',
                            'redirectQuery' => $deleteRedirectQuery,
                        ])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">Aucun service trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('services.partials.pagination', [
    'paginator' => $services,
    'label' => 'services',
    'perPage' => $perPage,
])
