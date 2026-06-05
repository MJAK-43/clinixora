@props(['paginator', 'label'])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = max(1, $paginator->lastPage());
    $hasItems = $paginator->total() > 0;
@endphp

<div class="mt-3 flex flex-col gap-2 border-t border-slate-100 pt-3 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
    @if ($hasItems)
        <p class="leading-relaxed">
            Affichage <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
            à <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
            sur <span class="font-medium text-slate-700">{{ $paginator->total() }}</span> {{ $label }}
        </p>
    @else
        <p class="leading-relaxed">Aucun {{ $label }} à afficher.</p>
    @endif

    <div class="flex items-center gap-2">
        <span class="rounded-md bg-slate-100 px-2.5 py-1 font-medium text-slate-600">
            Page {{ $currentPage }} / {{ $lastPage }}
        </span>
        @if ($hasItems && $paginator->onFirstPage())
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-300" aria-hidden="true">‹</span>
        @elseif ($hasItems)
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:border-sky-300 hover:bg-sky-50" aria-label="Page précédente">‹</a>
        @else
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-300" aria-hidden="true">‹</span>
        @endif

        @if ($hasItems && $paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:border-sky-300 hover:bg-sky-50" aria-label="Page suivante">›</a>
        @else
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-300" aria-hidden="true">›</span>
        @endif
    </div>
</div>
