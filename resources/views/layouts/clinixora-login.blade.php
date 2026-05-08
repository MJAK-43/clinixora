<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — {{ config('app.name', 'Clinixora') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen antialiased text-slate-100">
    {{-- Fond type maquette : bleu marine + vagues / points --}}
    <div class="fixed inset-0 -z-10 bg-[#060d1f]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0a1628] via-[#0c1a35] to-[#060d1f]"></div>
        <div class="absolute -left-20 top-20 h-80 w-80 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute -right-10 bottom-32 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>
        <div class="absolute left-1/4 top-1/3 h-2 w-2 rounded-full bg-cyan-400/30"></div>
        <div class="absolute right-1/3 top-1/4 h-1.5 w-1.5 rounded-full bg-cyan-300/40"></div>
        <div class="absolute right-1/4 bottom-1/3 h-2 w-2 rounded-full bg-sky-400/30"></div>
    </div>

    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6">
        @yield('content')
    </div>
</body>
</html>
