<x-clinixora-layout
    title="Pays"
    :customize-widgets="$customizeWidgets"
    :assistant-messages="$assistantMessages"
>
    @include('geography.partials.flash-toast')

    <div class="min-w-0 flex-1 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[90rem]">
            <nav class="text-sm text-slate-500" aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center gap-1">
                    <li><a href="{{ route('parametres') }}" class="hover:text-sky-600">Paramètres</a></li>
                    <li aria-hidden="true">›</li>
                    <li>Administration</li>
                    <li aria-hidden="true">›</li>
                    <li class="font-medium text-slate-800">Pays</li>
                </ol>
            </nav>

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pays</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Gérez la liste des pays. Un pays peut contenir plusieurs villes.
                    </p>
                </div>
                <a
                    href="{{ route('geography.countries.index', array_merge(request()->query(), ['create_country' => 1])) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-sky-600"
                >
                    <span aria-hidden="true">+</span> Ajouter un pays
                </a>
            </div>

            <div
                class="mt-8 grid grid-cols-1 gap-5 xl:grid-cols-3 xl:items-stretch"
                x-data="geographyPage({
                    baseUrl: @js(route('geography.countries.index')),
                    initialQuery: @js(request()->query()),
                })"
                @geography-search.window="applySearch($event.detail)"
            >
                @include('geography.partials.countries-panel')
                @include('geography.partials.cities-panel')
                @include('geography.partials.districts-panel')
            </div>
        </div>
    </div>

    @include('geography.partials.modals')
</x-clinixora-layout>
