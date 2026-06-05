@props([
    'editUrl',
    'deleteAction',
    'deleteConfirm',
    'redirectQuery' => [],
])

<div class="flex items-center justify-end gap-1">
    <a
        href="{{ $editUrl }}"
        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sky-600 hover:bg-sky-50"
        title="Modifier"
        aria-label="Modifier"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    </a>
    <form method="POST" action="{{ $deleteAction }}" class="inline">
        @csrf
        @method('DELETE')
        @foreach ($redirectQuery as $key => $val)
            @if ($val !== null && $val !== '')
                <input type="hidden" name="_redirect[{{ $key }}]" value="{{ $val }}">
            @endif
        @endforeach
        <button
            type="submit"
            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-500 hover:bg-red-50"
            title="Supprimer"
            aria-label="Supprimer"
            onclick="return confirm(@js($deleteConfirm))"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
    </form>
</div>
