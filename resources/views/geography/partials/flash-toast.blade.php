@if (session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 6000)"
        x-transition
        class="fixed bottom-6 right-6 z-[60] max-w-sm"
        role="status"
    >
        <div @class([
            'flex items-start gap-3 rounded-xl border px-4 py-3 shadow-lg',
            'border-emerald-200 bg-emerald-50 text-emerald-900' => session('success'),
            'border-red-200 bg-red-50 text-red-900' => session('error'),
        ])>
            <p class="flex-1 text-sm font-medium">{{ session('success') ?? session('error') }}</p>
            <button type="button" class="shrink-0 rounded p-1 opacity-70 hover:opacity-100" @click="show = false" aria-label="Fermer">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
@endif
