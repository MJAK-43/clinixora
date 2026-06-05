{{-- Modale création pays --}}
@if ($openCreateCountry || $errors->has('name') && ! $editingCountry && ! $editingCity && ! $editingDistrict)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter un pays</h3>
            <form method="POST" action="{{ route('geography.countries.store') }}" class="mt-4 space-y-4">
                @csrf
                @foreach (request()->query() as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="country_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="country_name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="country_code" class="block text-sm font-medium text-slate-700">Code (2 lettres) *</label>
                    <input id="country_code" name="code" type="text" maxlength="2" value="{{ old('code') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase @error('code') border-red-500 @enderror">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="country_flag" class="block text-sm font-medium text-slate-700">Code drapeau (optionnel)</label>
                    <input id="country_flag" name="flag_code" type="text" value="{{ old('flag_code') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->query()) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Modale édition pays --}}
@if ($editingCountry)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Modifier le pays</h3>
            <form method="POST" action="{{ route('geography.countries.update', $editingCountry) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                @foreach (request()->except(['edit_country']) as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="edit_country_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="edit_country_name" name="name" type="text" value="{{ old('name', $editingCountry->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="edit_country_code" class="block text-sm font-medium text-slate-700">Code *</label>
                    <input id="edit_country_code" name="code" type="text" maxlength="2" value="{{ old('code', $editingCountry->code) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="edit_country_flag" class="block text-sm font-medium text-slate-700">Code drapeau</label>
                    <input id="edit_country_flag" name="flag_code" type="text" value="{{ old('flag_code', $editingCountry->flag_code) }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingCountry->is_active)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->except('edit_country')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Modale création ville --}}
@if (($openCreateCity || ($errors->any() && request()->has('create_city'))) && $selectedCountry)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter une ville</h3>
            <p class="mt-1 text-sm text-slate-500">Pays : {{ $selectedCountry->name }}</p>
            <form method="POST" action="{{ route('geography.cities.store', $selectedCountry) }}" class="mt-4 space-y-4">
                @csrf
                @foreach (request()->query() as $key => $val)
                    @if ($val !== null && $val !== '' && ! in_array($key, ['create_city'], true))
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="city_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="city_name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="city_code" class="block text-sm font-medium text-slate-700">Code *</label>
                    <input id="city_code" name="code" type="text" value="{{ old('code') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->except('create_city')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Modale édition ville --}}
@if ($editingCity)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Modifier la ville</h3>
            <form method="POST" action="{{ route('geography.cities.update', $editingCity) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                @foreach (request()->except(['edit_city']) as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="edit_city_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="edit_city_name" name="name" type="text" value="{{ old('name', $editingCity->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="edit_city_code" class="block text-sm font-medium text-slate-700">Code *</label>
                    <input id="edit_city_code" name="code" type="text" value="{{ old('code', $editingCity->code) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingCity->is_active)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->except('edit_city')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Modale création quartier --}}
@if (($openCreateDistrict || request()->has('create_district')) && $selectedCity)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter un quartier</h3>
            <p class="mt-1 text-sm text-slate-500">Ville : {{ $selectedCity->name }}</p>
            <form method="POST" action="{{ route('geography.districts.store', $selectedCity) }}" class="mt-4 space-y-4">
                @csrf
                @foreach (request()->query() as $key => $val)
                    @if ($val !== null && $val !== '' && ! in_array($key, ['create_district'], true))
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="district_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="district_name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="district_code" class="block text-sm font-medium text-slate-700">Code *</label>
                    <input id="district_code" name="code" type="text" value="{{ old('code') }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->except('create_district')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Modale édition quartier --}}
@if ($editingDistrict)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Modifier le quartier</h3>
            <form method="POST" action="{{ route('geography.districts.update', $editingDistrict) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                @foreach (request()->except(['edit_district']) as $key => $val)
                    @if ($val !== null && $val !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <div>
                    <label for="edit_district_name" class="block text-sm font-medium text-slate-700">Nom *</label>
                    <input id="edit_district_name" name="name" type="text" value="{{ old('name', $editingDistrict->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="edit_district_code" class="block text-sm font-medium text-slate-700">Code *</label>
                    <input id="edit_district_code" name="code" type="text" value="{{ old('code', $editingDistrict->code) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingDistrict->is_active)) class="rounded border-slate-300 text-sky-600">
                    Actif
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('geography.countries.index', request()->except('edit_district')) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endif
