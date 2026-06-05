@props([
    'editUrl',
    'deleteAction',
    'deleteConfirm',
    'redirectQuery' => [],
])

<div x-data="{ open: false }" class="relative inline-block text-left" @click.stop>
    <button
        type="button"
        class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
        @click.stop="open = !open"
        aria-label="Actions"
    >
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110-4 2 2 0 010 4z"/></svg>
    </button>
    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        class="absolute right-0 z-30 mt-1 w-40 origin-top-right rounded-lg border border-slate-200 bg-white py-1 shadow-lg"
    >
        <a href="{{ $editUrl }}" class="block px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50" @click="open = false">Modifier</a>
        <form
            method="POST"
            action="{{ $deleteAction }}"
            class="block"
            @submit.stop
            @click.stop
        >
            @csrf
            @method('DELETE')
            @foreach ($redirectQuery as $key => $val)
                @if ($val !== null && $val !== '')
                    <input type="hidden" name="_redirect[{{ $key }}]" value="{{ $val }}">
                @endif
            @endforeach
            <button
                type="submit"
                class="w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50"
                onclick="return confirm(@js($deleteConfirm))"
            >
                Supprimer
            </button>
        </form>
    </div>
</div>
