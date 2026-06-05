<x-clinixora-layout
    title="Bloc opératoire"
    :customize-widgets="$customizeWidgets"
    :assistant-messages="$assistantMessages"
>
    @include('operating_blocks.partials.flash-toast')

    <div class="min-w-0 flex-1 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[90rem]">
            <nav class="text-sm text-slate-500" aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center gap-1">
                    <li><a href="{{ route('parametres') }}" class="hover:text-sky-600">Paramètres</a></li>
                    <li aria-hidden="true">›</li>
                    <li>Structure &amp; Organisation</li>
                    <li aria-hidden="true">›</li>
                    <li class="font-medium text-slate-800">Bloc opératoire</li>
                </ol>
            </nav>

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bloc opératoire</h1>
                    <p class="mt-1 text-sm text-slate-600">Gérez la liste des blocs opératoires.</p>
                </div>
                <a
                    href="{{ route('operating_blocks.index', array_merge(request()->query(), ['create_operating_block' => 1])) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-sky-600"
                >
                    <span aria-hidden="true">+</span> Ajouter un bloc
                </a>
            </div>

            <div
                class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-start"
                x-data="operatingBlocksPage({
                    baseUrl: @js(route('operating_blocks.index')),
                    initialQuery: @js(request()->query()),
                })"
                @operating-block-search.window="applySearch($event.detail)"
            >
                <div class="lg:col-span-3">
                    @include('operating_blocks.partials.filters-panel')
                </div>
                <div class="lg:col-span-9">
                    @include('operating_blocks.partials.table-panel')
                </div>
            </div>
        </div>
    </div>

    @include('operating_blocks.partials.modals')
</x-clinixora-layout>
