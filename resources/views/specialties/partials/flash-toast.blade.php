@if (session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition
        class="fixed bottom-24 right-4 z-50 max-w-sm rounded-lg px-4 py-3 text-sm font-medium shadow-lg sm:bottom-6"
        :class="@js(session('success') ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white')"
        role="status"
    >
        {{ session('success') ?? session('error') }}
    </div>
@endif
