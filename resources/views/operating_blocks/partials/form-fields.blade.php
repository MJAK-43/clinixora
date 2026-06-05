@props(['block' => null])

<div>
    <label for="block_name" class="block text-sm font-medium text-slate-700">Nom du bloc *</label>
    <input id="block_name" name="name" type="text" value="{{ old('name', $block?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="block_code" class="block text-sm font-medium text-slate-700">Code *</label>
    <input id="block_code" name="code" type="text" maxlength="20" value="{{ old('code', $block?->code) }}" required placeholder="BLOC-01" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase @error('code') border-red-500 @enderror">
    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="block_service_id" class="block text-sm font-medium text-slate-700">Service *</label>
    <select id="block_service_id" name="service_id" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('service_id') border-red-500 @enderror">
        <option value="">Sélectionner un service</option>
        @foreach ($services as $service)
            <option value="{{ $service->id }}" @selected((string) old('service_id', $block?->service_id) === (string) $service->id)>{{ $service->name }}</option>
        @endforeach
    </select>
    @error('service_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="block_location" class="block text-sm font-medium text-slate-700">Localisation *</label>
    <input id="block_location" name="location" type="text" value="{{ old('location', $block?->location) }}" required placeholder="Niveau 0" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('location') border-red-500 @enderror">
    @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="block_icon" class="block text-sm font-medium text-slate-700">Icône</label>
    <select id="block_icon" name="icon" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
        @foreach ($iconOptions as $option)
            <option value="{{ $option['value'] }}" @selected(old('icon', $block?->icon ?? 'scalpel') === $option['value'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $block?->is_active ?? true)) class="rounded border-slate-300 text-sky-600">
    Actif
</label>
