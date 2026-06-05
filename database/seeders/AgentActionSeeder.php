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
use App\Domain\Agent\Handlers\CareRooms\CreateCareRoomHandler;
use App\Domain\Agent\Handlers\CareRooms\DeleteCareRoomHandler;
use App\Domain\Agent\Handlers\CareRooms\ListCareRoomsHandler;
use App\Domain\Agent\Handlers\CareRooms\SearchCareRoomsHandler;
use App\Domain\Agent\Handlers\CareRooms\UpdateCareRoomHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\CreateOperatingBlockHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\DeleteOperatingBlockHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\ListOperatingBlocksHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\SearchOperatingBlocksHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\UpdateOperatingBlockHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\CreateReceptionRoomHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\DeleteReceptionRoomHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\ListReceptionRoomsHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\SearchReceptionRoomsHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\UpdateReceptionRoomHandler;
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
            // Blocs opératoires
            ['action_key' => ListOperatingBlocksHandler::ACTION_KEY, 'module' => 'operating_blocks', 'label' => 'Lister les blocs opératoires', 'description' => 'Affiche tous les blocs opératoires.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListOperatingBlocksHandler::class],
            ['action_key' => CreateOperatingBlockHandler::ACTION_KEY, 'module' => 'operating_blocks', 'label' => 'Créer un bloc opératoire', 'description' => 'Ajoute un nouveau bloc opératoire.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateOperatingBlockHandler::class],
            ['action_key' => UpdateOperatingBlockHandler::ACTION_KEY, 'module' => 'operating_blocks', 'label' => 'Modifier un bloc opératoire', 'description' => 'Met à jour un bloc opératoire.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateOperatingBlockHandler::class],
            ['action_key' => DeleteOperatingBlockHandler::ACTION_KEY, 'module' => 'operating_blocks', 'label' => 'Supprimer un bloc opératoire', 'description' => 'Supprime un bloc opératoire.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteOperatingBlockHandler::class],
            ['action_key' => SearchOperatingBlocksHandler::ACTION_KEY, 'module' => 'operating_blocks', 'label' => 'Rechercher un bloc opératoire', 'description' => 'Trouve un bloc par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchOperatingBlocksHandler::class],
            // Salles de soin
            ['action_key' => ListCareRoomsHandler::ACTION_KEY, 'module' => 'care_rooms', 'label' => 'Lister les salles de soin', 'description' => 'Affiche toutes les salles de soin.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListCareRoomsHandler::class],
            ['action_key' => CreateCareRoomHandler::ACTION_KEY, 'module' => 'care_rooms', 'label' => 'Créer une salle de soin', 'description' => 'Ajoute une nouvelle salle de soin.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateCareRoomHandler::class],
            ['action_key' => UpdateCareRoomHandler::ACTION_KEY, 'module' => 'care_rooms', 'label' => 'Modifier une salle de soin', 'description' => 'Met à jour une salle de soin.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateCareRoomHandler::class],
            ['action_key' => DeleteCareRoomHandler::ACTION_KEY, 'module' => 'care_rooms', 'label' => 'Supprimer une salle de soin', 'description' => 'Supprime une salle de soin.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteCareRoomHandler::class],
            ['action_key' => SearchCareRoomsHandler::ACTION_KEY, 'module' => 'care_rooms', 'label' => 'Rechercher une salle de soin', 'description' => 'Trouve une salle par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchCareRoomsHandler::class],
            // Salles d'accueil
            ['action_key' => ListReceptionRoomsHandler::ACTION_KEY, 'module' => 'reception_rooms', 'label' => 'Lister les salles d\'accueil', 'description' => 'Affiche toutes les salles d\'accueil.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => ListReceptionRoomsHandler::class],
            ['action_key' => CreateReceptionRoomHandler::ACTION_KEY, 'module' => 'reception_rooms', 'label' => 'Créer une salle d\'accueil', 'description' => 'Ajoute une nouvelle salle d\'accueil.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => CreateReceptionRoomHandler::class],
            ['action_key' => UpdateReceptionRoomHandler::ACTION_KEY, 'module' => 'reception_rooms', 'label' => 'Modifier une salle d\'accueil', 'description' => 'Met à jour une salle d\'accueil.', 'risk_level' => 'medium', 'requires_confirmation' => true, 'handler_class' => UpdateReceptionRoomHandler::class],
            ['action_key' => DeleteReceptionRoomHandler::ACTION_KEY, 'module' => 'reception_rooms', 'label' => 'Supprimer une salle d\'accueil', 'description' => 'Supprime une salle d\'accueil.', 'risk_level' => 'high', 'requires_confirmation' => true, 'handler_class' => DeleteReceptionRoomHandler::class],
            ['action_key' => SearchReceptionRoomsHandler::ACTION_KEY, 'module' => 'reception_rooms', 'label' => 'Rechercher une salle d\'accueil', 'description' => 'Trouve une salle par nom ou code.', 'risk_level' => 'low', 'requires_confirmation' => false, 'handler_class' => SearchReceptionRoomsHandler::class],
        ];

        foreach ($actions as $attrs) {
            AgentAction::query()->updateOrCreate(
                ['action_key' => $attrs['action_key']],
                $attrs + ['is_enabled' => true],
            );
        }
    }
}
