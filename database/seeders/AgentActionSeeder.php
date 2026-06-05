<?php

namespace Database\Seeders;

use App\Domain\Agent\Handlers\Geography\CreateCityHandler;
use App\Domain\Agent\Handlers\Geography\CreateCountryHandler;
use App\Domain\Agent\Handlers\Geography\CreateDistrictHandler;
use App\Domain\Agent\Handlers\Geography\DeleteCityHandler;
use App\Domain\Agent\Handlers\Geography\DeleteCountryHandler;
use App\Domain\Agent\Handlers\Geography\DeleteDistrictHandler;
use App\Domain\Agent\Handlers\Geography\ListCitiesHandler;
use App\Domain\Agent\Handlers\Geography\ListCountriesHandler;
use App\Domain\Agent\Handlers\Geography\ListDistrictsHandler;
use App\Domain\Agent\Handlers\Geography\SearchCitiesHandler;
use App\Domain\Agent\Handlers\Geography\SearchCountriesHandler;
use App\Domain\Agent\Handlers\Geography\SearchDistrictsHandler;
use App\Domain\Agent\Handlers\Geography\UpdateCityHandler;
use App\Domain\Agent\Handlers\Geography\UpdateCountryHandler;
use App\Domain\Agent\Handlers\Geography\UpdateDistrictHandler;
use App\Domain\Agent\Handlers\Services\CreateServiceHandler;
use App\Domain\Agent\Handlers\Services\DeleteServiceHandler;
use App\Domain\Agent\Handlers\Services\ListServicesHandler;
use App\Domain\Agent\Handlers\Services\SearchServicesHandler;
use App\Domain\Agent\Handlers\Services\UpdateServiceHandler;
use App\Domain\Agent\Handlers\Specialties\CreateSpecialtyHandler;
use App\Domain\Agent\Handlers\Specialties\DeleteSpecialtyHandler;
use App\Domain\Agent\Handlers\Specialties\ListSpecialtiesHandler;
use App\Domain\Agent\Handlers\Specialties\SearchSpecialtiesHandler;
use App\Domain\Agent\Handlers\Specialties\UpdateSpecialtyHandler;
use App\Models\AgentAction;
use Illuminate\Database\Seeder;

class AgentActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            // Pays
            ['action_key' => ListCountriesHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Lister les pays', 'description' => 'Affiche tous les pays.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListCountriesHandler::class],
            ['action_key' => CreateCountryHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Créer un pays', 'description' => 'Ajoute un nouveau pays.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateCountryHandler::class],
            ['action_key' => UpdateCountryHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Modifier un pays', 'description' => 'Met à jour nom et code d’un pays.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateCountryHandler::class],
            ['action_key' => DeleteCountryHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Supprimer un pays', 'description' => 'Supprime un pays sans villes.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteCountryHandler::class],
            ['action_key' => SearchCountriesHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Rechercher un pays', 'description' => 'Trouve un pays par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchCountriesHandler::class],
            // Villes
            ['action_key' => ListCitiesHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Lister les villes', 'description' => 'Affiche les villes d’un pays.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListCitiesHandler::class],
            ['action_key' => CreateCityHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Créer une ville', 'description' => 'Ajoute une ville dans un pays.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateCityHandler::class],
            ['action_key' => UpdateCityHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Modifier une ville', 'description' => 'Met à jour une ville.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateCityHandler::class],
            ['action_key' => DeleteCityHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Supprimer une ville', 'description' => 'Supprime une ville et ses quartiers.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteCityHandler::class],
            ['action_key' => SearchCitiesHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Rechercher une ville', 'description' => 'Trouve des villes par nom ou code dans un pays.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchCitiesHandler::class],
            // Quartiers
            ['action_key' => ListDistrictsHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Lister les quartiers', 'description' => 'Affiche les quartiers d’une ville.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListDistrictsHandler::class],
            ['action_key' => CreateDistrictHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Créer un quartier', 'description' => 'Ajoute un quartier dans une ville.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateDistrictHandler::class],
            ['action_key' => UpdateDistrictHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Modifier un quartier', 'description' => 'Met à jour un quartier.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateDistrictHandler::class],
            ['action_key' => DeleteDistrictHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Supprimer un quartier', 'description' => 'Supprime un quartier.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteDistrictHandler::class],
            ['action_key' => SearchDistrictsHandler::ACTION_KEY, 'module' => 'geography', 'label' => 'Rechercher un quartier', 'description' => 'Trouve des quartiers par nom ou code dans une ville.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchDistrictsHandler::class],
            // Spécialités
            ['action_key' => ListSpecialtiesHandler::ACTION_KEY, 'module' => 'specialties', 'label' => 'Lister les spécialités', 'description' => 'Affiche toutes les spécialités médicales.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListSpecialtiesHandler::class],
            ['action_key' => CreateSpecialtyHandler::ACTION_KEY, 'module' => 'specialties', 'label' => 'Créer une spécialité', 'description' => 'Ajoute une nouvelle spécialité.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateSpecialtyHandler::class],
            ['action_key' => UpdateSpecialtyHandler::ACTION_KEY, 'module' => 'specialties', 'label' => 'Modifier une spécialité', 'description' => 'Met à jour nom et code d’une spécialité.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateSpecialtyHandler::class],
            ['action_key' => DeleteSpecialtyHandler::ACTION_KEY, 'module' => 'specialties', 'label' => 'Supprimer une spécialité', 'description' => 'Supprime une spécialité.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteSpecialtyHandler::class],
            ['action_key' => SearchSpecialtiesHandler::ACTION_KEY, 'module' => 'specialties', 'label' => 'Rechercher une spécialité', 'description' => 'Trouve une spécialité par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchSpecialtiesHandler::class],
            // Services
            ['action_key' => ListServicesHandler::ACTION_KEY, 'module' => 'services', 'label' => 'Lister les services', 'description' => 'Affiche tous les services cliniques.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListServicesHandler::class],
            ['action_key' => CreateServiceHandler::ACTION_KEY, 'module' => 'services', 'label' => 'Créer un service', 'description' => 'Ajoute un nouveau service.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateServiceHandler::class],
            ['action_key' => UpdateServiceHandler::ACTION_KEY, 'module' => 'services', 'label' => 'Modifier un service', 'description' => 'Met à jour nom et code d’un service.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateServiceHandler::class],
            ['action_key' => DeleteServiceHandler::ACTION_KEY, 'module' => 'services', 'label' => 'Supprimer un service', 'description' => 'Supprime un service.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteServiceHandler::class],
            ['action_key' => SearchServicesHandler::ACTION_KEY, 'module' => 'services', 'label' => 'Rechercher un service', 'description' => 'Trouve un service par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchServicesHandler::class],
        ];

        foreach ($actions as $attrs) {
            AgentAction::query()->updateOrCreate(
                ['action_key' => $attrs['action_key']],
                $attrs + ['is_enabled' => true],
            );
        }
    }
}
