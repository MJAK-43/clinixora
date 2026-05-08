@php
    $maxAct = max($activitySeries) ?: 1;
    $minAct = min($activitySeries);
    $rangeAct = max($maxAct - $minAct, 1);
    $nAct = count($activitySeries);
    $maxCash = max($cashSeries) ?: 1;
@endphp

<x-clinixora-layout
    title="Tableau de bord"
    :customize-widgets="$customizeWidgets"
    :assistant-messages="$assistantMessages"
>
    <div class="min-w-0 flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tableau de bord</h1>
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg border-2 border-sky-500 bg-white px-4 py-2 text-sm font-semibold text-sky-600 shadow-sm transition hover:bg-sky-50"
                        @click="customizeOpen = true"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Personnaliser
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600"
                        @click="showAll()"
                    >
                        Tout afficher
                    </button>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($kpis as $kpi)
                    <div
                        x-show="widgets['{{ $kpi['id'] }}']"
                        x-transition
                        class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-50 text-sky-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <span class="text-xs font-medium text-slate-500">{{ $kpi['label'] }}</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900">{{ $kpi['value'] }}{{ $kpi['suffix'] }}</p>
                        <p class="mt-1 text-xs font-medium {{ $kpi['up'] ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $kpi['up'] ? '↗' : '↘' }} {{ $kpi['trend'] }} % vs hier
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div
                    x-show="widgets['chart_acts_volume_timeline']"
                    x-transition
                    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="border-b border-slate-100 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">Activité 7 jours</h2>
                        <p class="text-xs text-slate-500">Volume d’actes (tous types) — id <code class="rounded bg-slate-100 px-1 text-[10px]">chart_acts_volume_timeline</code></p>
                    </div>
                    <div class="bg-[#0F172A] px-4 py-6">
                        <svg viewBox="0 0 400 160" class="h-48 w-full" preserveAspectRatio="none" aria-hidden="true">
                            <polyline fill="none" stroke="#38bdf8" stroke-width="2.5" points="{{ $activityPolyline }}" vector-effect="non-scaling-stroke" />
                            @foreach ($activitySeries as $i => $v)
                                @php
                                    $x = $nAct === 1 ? 200 : ($i / ($nAct - 1)) * 400;
                                    $y = 160 - (($v - $minAct) / $rangeAct) * 160;
                                @endphp
                                <circle cx="{{ $x }}" cy="{{ $y }}" r="5" fill="#0ea5e9" stroke="#e0f2fe" stroke-width="2" />
                            @endforeach
                        </svg>
                        <div class="mt-2 flex justify-between text-[11px] font-medium text-slate-400">
                            @foreach ($chartLabels as $lab)
                                <span>{{ $lab }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div
                    x-show="widgets['chart_encashments_timeline']"
                    x-transition
                    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="border-b border-slate-100 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">Encaissements</h2>
                        <p class="text-xs text-slate-500">Montants par jour — id <code class="rounded bg-slate-100 px-1 text-[10px]">chart_encashments_timeline</code></p>
                    </div>
                    <div class="flex h-52 flex-col justify-end bg-[#0F172A] px-4 pb-6 pt-4">
                        <div class="flex h-40 items-end justify-between gap-1.5">
                            @foreach ($cashSeries as $v)
                                @php $hPct = round(($v / $maxCash) * 100); @endphp
                                <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                                    <div
                                        class="w-full max-w-[2.5rem] rounded-t-md bg-gradient-to-t from-sky-600 to-sky-400 shadow-lg shadow-sky-900/30"
                                        style="height: {{ max($hPct, 8) }}%"
                                        title="{{ number_format($v, 0, ',', ' ') }} €"
                                    ></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 flex justify-between gap-1 text-[11px] font-medium text-slate-400">
                            @foreach ($chartLabels as $lab)
                                <span class="min-w-0 flex-1 truncate text-center">{{ $lab }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-10 text-center text-xs text-slate-400">Données mises à jour il y a 5 minutes</p>
        </div>
    </div>
</x-clinixora-layout>
