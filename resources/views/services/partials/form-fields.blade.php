@props(['service' => null])

<div>
    <label for="service_name" class="block text-sm font-medium text-slate-700">Nom *</label>
    <input id="service_name" name="name" type="text" value="{{ old('name', $service?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="service_code" class="block text-sm font-medium text-slate-700">Code *</label>
    <input id="service_code" name="code" type="text" maxlength="10" value="{{ old('code', $service?->code) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase @error('code') border-red-500 @enderror">
    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="service_description" class="block text-sm font-medium text-slate-700">Description</label>
    <textarea id="service_description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('description') border-red-500 @enderror">{{ old('description', $service?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="service_icon" class="block text-sm font-medium text-slate-700">Icône</label>
    <select id="service_icon" name="icon" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
        @foreach ($iconOptions as $option)
            <option value="{{ $option['value'] }}" @selected(old('icon', $service?->icon ?? 'briefcase') === $option['value'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true)) class="rounded border-slate-300 text-sky-600">
    Actif
</label>
