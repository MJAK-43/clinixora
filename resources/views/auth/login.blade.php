@extends('layouts.clinixora-login')

@section('title', 'Connexion')

@section('content')
    <div class="w-full max-w-md">
        {{-- Carte principale --}}
        <div class="rounded-2xl bg-white p-8 shadow-2xl shadow-black/40 ring-1 ring-white/10">
            {{-- Logo zone --}}
            <div class="mb-8 flex flex-col items-center text-center">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-sky-600 to-cyan-500 shadow-lg shadow-cyan-500/30">
                        {{-- Icône croix + motif central simplifié --}}
                        <svg class="h-9 w-9 text-white" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M18 4h4v12h12v4H22v12h-4V20H6v-4h12V4z" fill="currentColor" opacity=".95"/>
                            <circle cx="20" cy="20" r="6" stroke="currentColor" stroke-width="1.5" fill="none" opacity=".6"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="text-2xl font-bold tracking-tight">
                            <span class="text-slate-800">Clini</span><span class="bg-gradient-to-r from-sky-600 to-cyan-500 bg-clip-text text-transparent">Xora</span>
                        </span>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">by INOVIXORA</p>
                    </div>
                </div>
                <h1 class="text-xl font-semibold text-slate-800">Connexion</h1>
            </div>

            <x-auth-session-status class="mb-4 text-center text-sm text-red-600" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Identifiant = email (validation Laravel inchangée) --}}
                <div>
                    <label for="email" class="sr-only">{{ __('Identifiant') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sky-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="Identifiant ou e-mail"
                            class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-3 text-slate-800 placeholder:text-slate-400 shadow-inner focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                </div>

                {{-- Mot de passe + afficher/masquer --}}
                <div x-data="{ show: false }">
                    <label for="password" class="sr-only">{{ __('Mot de passe') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sky-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password"
                            placeholder="Mot de passe"
                            class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-12 text-slate-800 placeholder:text-slate-400 shadow-inner focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-sky-600" tabindex="-1">
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500" />
                        <span>{{ __('Se souvenir de moi') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="font-medium text-sky-600 hover:text-sky-800" href="{{ route('password.request') }}">{{ __('Mot de passe oublié ?') }}</a>
                    @endif
                </div>

                <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-500 py-3.5 text-sm font-semibold text-white shadow-lg shadow-cyan-500/25 transition hover:from-sky-500 hover:to-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Se connecter
                </button>
            </form>
        </div>

        {{-- Bandeau Nos contacts --}}
        <div class="mt-6 rounded-2xl bg-white/95 px-6 py-4 shadow-lg shadow-black/20 ring-1 ring-white/10 backdrop-blur">
            <p class="mb-3 flex items-center justify-center gap-2 text-center text-sm font-medium text-slate-700">
                <svg class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Nos contacts
            </p>
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 font-medium text-[#1877F2] hover:underline">
                    <span class="inline-block h-5 w-5 rounded-full bg-[#1877F2] text-center text-xs leading-5 text-white">f</span>
                    Facebook
                </a>
                <span class="hidden text-slate-300 sm:inline">|</span>
                <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 font-medium text-[#0A66C2] hover:underline">
                    <span class="inline-block h-5 w-5 rounded bg-[#0A66C2] text-center text-[10px] font-bold leading-5 text-white">in</span>
                    LinkedIn
                </a>
                <span class="hidden text-slate-300 sm:inline">|</span>
                <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 font-medium text-[#25D366] hover:underline">
                    <span class="inline-block h-5 w-5 rounded-full bg-[#25D366] text-center text-xs leading-5 text-white">W</span>
                    WhatsApp
                </a>
            </div>
        </div>

        @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-cyan-200/90">
                {{ __('Pas encore de compte ?') }}
                <a href="{{ route('register') }}" class="font-semibold text-white underline decoration-cyan-400 underline-offset-2 hover:text-cyan-100">{{ __('Créer un compte') }}</a>
            </p>
        @endif
    </div>
@endsection
