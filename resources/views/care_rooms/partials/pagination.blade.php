@props(['paginator', 'label', 'perPage', 'indexRoute' => 'care_rooms.index', 'pageParam' => 'care_rooms_page'])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = max(1, $paginator->lastPage());
    $hasItems = $paginator->total() > 0;
    $queryExceptPage = collect(request()->query())->except($pageParam)->all();
@endphp

<div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
    @if ($hasItems)
        <p class="leading-relaxed">
            Affichage de <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
            à <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
            sur <span class="font-medium text-slate-700">{{ $paginator->total() }}</span> {{ $label }}
        </p>
    @else
        <p class="leading-relaxed">Aucune {{ $label }} à afficher.</p>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <form method="GET" action="{{ route($indexRoute) }}" class="flex items-center gap-2">
            @foreach ($queryExceptPage as $key => $val)
                @if ($val !== null && $val !== '' && $key !== 'per_page')
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
            <label for="per_page" class="sr-only">Éléments par page</label>
            <select id="per_page" name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-700">
                @foreach ([10, 25, 50] as $size)
                    <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} / page</option>
                @endforeach
            </select>
        </form>

        <div class="flex items-center gap-1">
            @if ($hasItems && ! $paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 px-2 text-slate-600 hover:border-sky-300 hover:bg-sky-50" aria-label="Page précédente">‹</a>
            @else
                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 px-2 text-slate-300" aria-hidden="true">‹</span>
            @endif

            @for ($page = max(1, $currentPage - 1); $page <= min($lastPage, $currentPage + 1); $page++)
                @if ($page === $currentPage)
                    <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-sky-500 px-2 font-medium text-white">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 px-2 text-slate-600 hover:border-sky-300 hover:bg-sky-50">{{ $page }}</a>
                @endif
            @endfor

            @if ($hasItems && $paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 px-2 text-slate-600 hover:border-sky-300 hover:bg-sky-50" aria-label="Page suivante">›</a>
            @else
                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 px-2 text-slate-300" aria-hidden="true">›</span>
            @endif
        </div>
    </div>
</div>
