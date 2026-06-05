@props(['specialty' => null])

<div>
    <label for="specialty_name" class="block text-sm font-medium text-slate-700">Nom *</label>
    <input id="specialty_name" name="name" type="text" value="{{ old('name', $specialty?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="specialty_code" class="block text-sm font-medium text-slate-700">Code *</label>
    <input id="specialty_code" name="code" type="text" maxlength="10" value="{{ old('code', $specialty?->code) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase @error('code') border-red-500 @enderror">
    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="specialty_description" class="block text-sm font-medium text-slate-700">Description</label>
    <textarea id="specialty_description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('description') border-red-500 @enderror">{{ old('description', $specialty?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="specialty_icon" class="block text-sm font-medium text-slate-700">Icône</label>
    <select id="specialty_icon" name="icon" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
        @foreach ($iconOptions as $option)
            <option value="{{ $option['value'] }}" @selected(old('icon', $specialty?->icon ?? 'stethoscope') === $option['value'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specialty?->is_active ?? true)) class="rounded border-slate-300 text-sky-600">
    Actif
</label>
