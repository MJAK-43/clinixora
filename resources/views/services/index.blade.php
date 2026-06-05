<x-clinixora-layout
    title="Services"
    :customize-widgets="$customizeWidgets"
    :assistant-messages="$assistantMessages"
>
    @include('services.partials.flash-toast')

    <div class="min-w-0 flex-1 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[90rem]">
            <nav class="text-sm text-slate-500" aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center gap-1">
                    <li><a href="{{ route('parametres') }}" class="hover:text-sky-600">Paramètres</a></li>
                    <li aria-hidden="true">›</li>
                    <li>Structure &amp; Organisation</li>
                    <li aria-hidden="true">›</li>
                    <li class="font-medium text-slate-800">Services</li>
                </ol>
            </nav>

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Services</h1>
                    <p class="mt-1 text-sm text-slate-600">Gérez la liste des services de la clinique.</p>
                </div>
                <a
                    href="{{ route('services.index', array_merge(request()->query(), ['create_service' => 1])) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-sky-600"
                >
                    <span aria-hidden="true">+</span> Ajouter un service
                </a>
            </div>

            <div
                class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-start"
                x-data="servicesPage({
                    baseUrl: @js(route('services.index')),
                    initialQuery: @js(request()->query()),
                })"
                @service-search.window="applySearch($event.detail)"
            >
                <div class="lg:col-span-3">
                    @include('services.partials.filters-panel')
                </div>
                <div class="lg:col-span-9">
                    @include('services.partials.table-panel')
                </div>
            </div>
        </div>
    </div>

    @include('services.partials.modals')
</x-clinixora-layout>
