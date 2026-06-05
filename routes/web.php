<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Geography\CityController;
use App\Http\Controllers\Geography\CountryController;
use App\Http\Controllers\Geography\CountryPageController;
use App\Http\Controllers\Geography\DistrictController;
use App\Http\Controllers\Services\ServiceController;
use App\Http\Controllers\Services\ServicePageController;
use App\Http\Controllers\Specialties\SpecialtyController;
use App\Http\Controllers\Specialties\SpecialtyPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Agent\AgentCatalogController;
use App\Http\Controllers\Agent\AgentChatController;
use App\Http\Controllers\Settings\AssistantSettingsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/parametres', SettingsController::class)
    ->middleware(['auth', 'verified'])
    ->name('parametres');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/parametres/assistant', [AssistantSettingsController::class, 'edit'])
        ->name('parametres.assistant');
    Route::patch('/parametres/assistant', [AssistantSettingsController::class, 'update'])
        ->name('parametres.assistant.update');

    Route::get('/api/agent/catalog', AgentCatalogController::class)
        ->name('agent.catalog');

    Route::post('/api/agent/messages', [AgentChatController::class, 'store'])
        ->name('agent.messages.store');
    Route::post('/api/agent/confirm', [AgentChatController::class, 'confirm'])
        ->name('agent.confirm');
});

Route::middleware(['auth', 'verified'])->prefix('parametres/structure')->group(function () {
    Route::get('services', ServicePageController::class)->name('services.index');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    Route::patch('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('specialites', SpecialtyPageController::class)->name('specialties.index');
    Route::post('specialites', [SpecialtyController::class, 'store'])->name('specialties.store');
    Route::patch('specialites/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
    Route::delete('specialites/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('parametres/administration')->group(function () {
    Route::get('pays', CountryPageController::class)->name('geography.countries.index');
    Route::post('pays', [CountryController::class, 'store'])->name('geography.countries.store');
    Route::patch('pays/{country}', [CountryController::class, 'update'])->name('geography.countries.update');
    Route::delete('pays/{country}', [CountryController::class, 'destroy'])->name('geography.countries.destroy');

    Route::post('pays/{country}/villes', [CityController::class, 'store'])->name('geography.cities.store');
    Route::patch('villes/{city}', [CityController::class, 'update'])->name('geography.cities.update');
    Route::delete('villes/{city}', [CityController::class, 'destroy'])->name('geography.cities.destroy');

    Route::post('villes/{city}/quartiers', [DistrictController::class, 'store'])->name('geography.districts.store');
    Route::patch('quartiers/{district}', [DistrictController::class, 'update'])->name('geography.districts.update');
    Route::delete('quartiers/{district}', [DistrictController::class, 'destroy'])->name('geography.districts.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
