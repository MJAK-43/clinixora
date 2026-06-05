@php
    $deleteRedirectQuery = collect(request()->query())
        ->except(['edit_operating_block', 'create_operating_block'])
        ->all();
@endphp

<div class="min-h-0 flex-1 overflow-auto">
    <table class="min-w-full text-sm">
        <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 shadow-sm">
            <tr>
                <th class="w-12 px-4 py-3"><span class="sr-only">Icône</span></th>
                <th class="px-4 py-3">Nom du bloc</th>
                <th class="px-4 py-3">Code</th>
                <th class="hidden px-4 py-3 md:table-cell">Service</th>
                <th class="hidden px-4 py-3 lg:table-cell">Localisation</th>
                <th class="px-4 py-3">Statut</th>
                <th class="w-24 px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($blocks as $block)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50">
                            @include('operating_blocks.partials.icon', ['icon' => $block->icon])
                        </span>
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $block->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $block->code }}</td>
                    <td class="hidden px-4 py-3 text-slate-600 md:table-cell">{{ $block->service?->name }}</td>
                    <td class="hidden px-4 py-3 text-slate-500 lg:table-cell">{{ $block->location }}</td>
                    <td class="px-4 py-3">
                        @if ($block->is_active)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Actif</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Inactif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @include('operating_blocks.partials.row-actions', [
                            'editUrl' => route('operating_blocks.index', array_merge(request()->query(), ['edit_operating_block' => $block->id])),
                            'deleteAction' => route('operating_blocks.destroy', $block),
                            'deleteConfirm' => 'Supprimer le bloc « '.$block->name.' » ?',
                            'redirectQuery' => $deleteRedirectQuery,
                        ])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-slate-500">Aucun bloc opératoire trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('operating_blocks.partials.pagination', [
    'paginator' => $blocks,
    'label' => 'blocs opératoires',
    'perPage' => $perPage,
])
