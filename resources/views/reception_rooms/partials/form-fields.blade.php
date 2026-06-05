@props(['room' => null])

<div>
    <label for="room_name" class="block text-sm font-medium text-slate-700">Nom de la salle *</label>
    <input id="room_name" name="name" type="text" value="{{ old('name', $room?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="room_code" class="block text-sm font-medium text-slate-700">Code *</label>
    <input id="room_code" name="code" type="text" maxlength="20" value="{{ old('code', $room?->code) }}" required placeholder="ACC-01" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase @error('code') border-red-500 @enderror">
    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="room_location" class="block text-sm font-medium text-slate-700">Localisation *</label>
    <input id="room_location" name="location" type="text" value="{{ old('location', $room?->location) }}" required placeholder="Hall d'entrée" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm @error('location') border-red-500 @enderror">
    @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="room_icon" class="block text-sm font-medium text-slate-700">Icône</label>
    <select id="room_icon" name="icon" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
        @foreach ($iconOptions as $option)
            <option value="{{ $option['value'] }}" @selected(old('icon', $room?->icon ?? 'briefcase') === $option['value'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $room?->is_active ?? true)) class="rounded border-slate-300 text-sky-600">
    Actif
</label>
