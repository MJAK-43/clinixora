<x-clinixora-layout
    title="Paramètres"
    :customize-widgets="$customizeWidgets"
    :assistant-messages="$assistantMessages"
>
    <div class="min-w-0 flex-1 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                <div class="min-w-0 flex-1">
                    <h1 id="parametres-page-title" class="text-2xl font-bold tracking-tight text-slate-900">Paramètres</h1>
                    <p class="mt-1 max-w-2xl text-sm text-slate-600">
                        Gérez les configurations de votre établissement et personnalisez votre expérience.
                    </p>
                </div>
                <div class="flex w-full shrink-0 flex-col gap-3 sm:flex-row sm:items-center sm:justify-end lg:ml-auto lg:w-auto">
                    <label class="relative block w-full sm:w-72 lg:w-80">
                        <span class="sr-only">Rechercher un paramètre</span>
                        <input
                            type="search"
                            class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                            placeholder="Rechercher un paramètre..."
                            autocomplete="off"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            @include('partials.settings-icon', ['name' => 'search', 'class' => 'h-4 w-4'])
                        </span>
                    </label>
                    <a
                        href="#"
                        class="inline-flex items-center gap-2 whitespace-nowrap py-2 text-sm font-medium text-sky-600 transition hover:text-sky-700 hover:underline"
                    >
                        @include('partials.settings-icon', ['name' => 'clock', 'class' => 'h-4 w-4'])
                        Voir les journaux
                    </a>
                </div>
            </div>

            <section class="mt-8" aria-labelledby="quick-access-heading">
                <h2 id="quick-access-heading" class="text-base font-semibold text-slate-900">Accès rapide</h2>
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5 lg:gap-3">
                    @foreach ($quickAccess as $card)
                        <a
                            href="{{ $card['href'] }}"
                            class="group flex min-w-0 items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 transition hover:border-sky-200 hover:shadow-sm"
                        >
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $card['iconTile'] ?? 'bg-sky-50 text-sky-600' }}">
                                @include('partials.settings-icon', ['name' => $card['icon'], 'class' => 'h-5 w-5'])
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold leading-tight text-slate-900 group-hover:text-sky-700">{{ $card['title'] }}</span>
                                <span class="mt-0.5 block text-xs leading-snug text-slate-500">{{ $card['description'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>

            <div class="mt-10 grid grid-cols-1 items-start gap-8 lg:grid-cols-4">
                <section class="min-w-0 lg:col-span-3" aria-labelledby="categories-heading">
                    <h2 id="categories-heading" class="text-sm font-semibold text-slate-800">Paramètres par catégorie</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($categories as $cat)
                            <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white {{ $cat['iconWrap'] }}">
                                        @include('partials.settings-icon', ['name' => $cat['icon'], 'class' => 'h-5 w-5'])
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-semibold text-slate-900">{{ $cat['title'] }}</h3>
                                        <ul class="mt-3 space-y-2 text-sm">
                                            @foreach ($cat['links'] as $link)
                                                @php
                                                    $linkLabel = is_array($link) ? $link['label'] : $link;
                                                    $linkHref = is_array($link) ? ($link['href'] ?? '#') : '#';
                                                @endphp
                                                <li>
                                                    <a href="{{ $linkHref }}" class="text-slate-600 transition hover:text-sky-600">{{ $linkLabel }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <aside class="rounded-xl border border-sky-100 bg-sky-50/80 p-5 shadow-sm lg:col-span-1 lg:sticky lg:top-6" aria-labelledby="tips-heading">
                    <div class="flex items-center gap-2 text-sky-900">
                        @include('partials.settings-icon', ['name' => 'lightbulb', 'class' => 'h-5 w-5'])
                        <h2 id="tips-heading" class="text-sm font-semibold">Conseils</h2>
                    </div>
                    <ul class="mt-4 space-y-4">
                        @foreach ($tips as $tip)
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sky-600 shadow-sm">
                                    @include('partials.settings-icon', ['name' => $tip['icon'], 'class' => 'h-4 w-4'])
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $tip['title'] }}</p>
                                    <p class="mt-0.5 text-sm text-slate-600">{{ $tip['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            </div>

            <p class="mt-12 flex items-center justify-center gap-2 text-center text-xs text-slate-500">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Dernière synchronisation : il y a 2 minutes
            </p>
        </div>
    </div>
</x-clinixora-layout>
