@if ($openCreateSpecialty || ($errors->any() && ! $editingSpecialty && request()->has('create_specialty')))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter une spécialité</h3>
            <form method="POST" action="{{ route('specialties.store') }}" class="mt-4 space-y-4">
                @csrf
                @foreach (request()->query() as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                @include('specialties.partials.form-fields')
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('specialties.index', request()->except('create_specialty')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if ($editingSpecialty)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Modifier la spécialité</h3>
            <form method="POST" action="{{ route('specialties.update', $editingSpecialty) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                @foreach (request()->except(['edit_specialty']) as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                @include('specialties.partials.form-fields', ['specialty' => $editingSpecialty])
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('specialties.index', request()->except('edit_specialty')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif
