@if ($openCreateService || ($errors->any() && ! $editingService && request()->has('create_service')))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter un service</h3>
            <form method="POST" action="{{ route('services.store') }}" class="mt-4 space-y-4">
                @csrf
                @foreach (request()->query() as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                @include('services.partials.form-fields')
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('services.index', request()->except('create_service')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if ($editingService)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Modifier le service</h3>
            <form method="POST" action="{{ route('services.update', $editingService) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                @foreach (request()->except(['edit_service']) as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                @include('services.partials.form-fields', ['service' => $editingService])
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('services.index', request()->except('edit_service')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif
