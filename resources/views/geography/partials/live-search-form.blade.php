@props([
    'panel',
    'searchName',
    'searchValue' => '',
    'placeholder',
    'formClass' => 'mt-3',
])

<div class="{{ $formClass }}">
    <label class="sr-only">{{ $placeholder }}</label>
    <input
        type="search"
        name="{{ $searchName }}"
        value="{{ $searchValue }}"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
        @input.debounce.400ms="$dispatch('geography-search', { panel: '{{ $panel }}', param: '{{ $searchName }}', value: $event.target.value })"
    >
</div>
