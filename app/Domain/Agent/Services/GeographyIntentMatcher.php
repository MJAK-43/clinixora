<?php

namespace App\Domain\Agent\Services;

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

class GeographyIntentMatcher
{
    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $text): ?array
    {
        $matchers = [
            'matchDeleteDistrict',
            'matchDeleteCity',
            'matchDeleteCountry',
            'matchUpdateDistrict',
            'matchUpdateCity',
            'matchUpdateCountry',
            'matchCreateDistrict',
            'matchCreateCity',
            'matchCreateCountry',
            'matchSearchDistrict',
            'matchSearchCity',
            'matchSearchCountry',
            'matchListDistricts',
            'matchListCities',
            'matchListCountries',
        ];

        foreach ($matchers as $method) {
            if ($result = $this->{$method}($text)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchListCountries(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?pays/u', $text)) {
            return ['action_key' => ListCountriesHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchListCities(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?villes(?:\s+(?:de|du|dans)\s+(.+?))?(?:\s+contenant\s+(.+))?$/u', $text, $m)) {
            return [
                'action_key' => ListCitiesHandler::ACTION_KEY,
                'params' => array_filter([
                    'country' => isset($m[1]) ? $this->titleCase(trim($m[1])) : 'Cameroun',
                    'search' => isset($m[2]) ? $this->titleCase(trim($m[2])) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchListDistricts(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?quartiers(?:\s+(?:de|du|dans|à)\s+(.+?))?(?:\s+contenant\s+(.+))?$/u', $text, $m)) {
            return [
                'action_key' => ListDistrictsHandler::ACTION_KEY,
                'params' => array_filter([
                    'city' => isset($m[1]) ? $this->titleCase(trim($m[1])) : null,
                    'search' => isset($m[2]) ? $this->titleCase(trim($m[2])) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateCountry(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:le\s+)?pays\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => CreateCountryHandler::ACTION_KEY,
                'params' => ['name' => $this->titleCase(trim($m[1])), 'code' => strtoupper(trim($m[2]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateCity(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouvelle?)\s+(?:la\s+)?ville\s+(.+?)\s+code\s+([a-z0-9]+)(?:\s+(?:dans|en|pour)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => CreateCityHandler::ACTION_KEY,
                'params' => array_filter([
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                    'country' => isset($m[3]) ? $this->titleCase(trim($m[3])) : 'Cameroun',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateDistrict(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:le\s+)?quartier\s+(.+?)\s+code\s+([a-z0-9]+)(?:\s+(?:dans|à|pour)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => CreateDistrictHandler::ACTION_KEY,
                'params' => array_filter([
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                    'city' => isset($m[3]) ? $this->titleCase(trim($m[3])) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateCountry(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:le\s+)?pays\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => UpdateCountryHandler::ACTION_KEY,
                'params' => [
                    'country' => $this->titleCase(trim($m[1])),
                    'name' => $this->titleCase(trim($m[2])),
                    'code' => strtoupper(trim($m[3])),
                ],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateCity(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:la\s+)?ville\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9]+)(?:\s+(?:dans|en)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => UpdateCityHandler::ACTION_KEY,
                'params' => array_filter([
                    'city' => $this->titleCase(trim($m[1])),
                    'name' => $this->titleCase(trim($m[2])),
                    'code' => strtoupper(trim($m[3])),
                    'country' => isset($m[4]) ? $this->titleCase(trim($m[4])) : 'Cameroun',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateDistrict(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:le\s+)?quartier\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9]+)(?:\s+(?:dans|à)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => UpdateDistrictHandler::ACTION_KEY,
                'params' => array_filter([
                    'district' => $this->titleCase(trim($m[1])),
                    'name' => $this->titleCase(trim($m[2])),
                    'code' => strtoupper(trim($m[3])),
                    'city' => isset($m[4]) ? $this->titleCase(trim($m[4])) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchDeleteCountry(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:le\s+)?pays\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteCountryHandler::ACTION_KEY,
                'params' => ['country' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchDeleteCity(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:la\s+)?ville\s+(.+?)(?:\s+(?:dans|en)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => DeleteCityHandler::ACTION_KEY,
                'params' => array_filter([
                    'city' => $this->titleCase(trim($m[1])),
                    'country' => isset($m[2]) ? $this->titleCase(trim($m[2])) : 'Cameroun',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchDeleteDistrict(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:le\s+)?quartier\s+(.+?)(?:\s+(?:dans|à)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => DeleteDistrictHandler::ACTION_KEY,
                'params' => array_filter([
                    'district' => $this->titleCase(trim($m[1])),
                    'city' => isset($m[2]) ? $this->titleCase(trim($m[2])) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchCountry(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:le\s+)?pays\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchCountriesHandler::ACTION_KEY,
                'params' => ['search' => trim($m[1])],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchCity(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:la\s+)?ville\s+(.+?)(?:\s+(?:dans|en|du|de|pour)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => SearchCitiesHandler::ACTION_KEY,
                'params' => array_filter([
                    'search' => trim($m[1]),
                    'country' => isset($m[2]) ? $this->titleCase(trim($m[2])) : 'Cameroun',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchDistrict(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:le\s+)?quartier\s+(.+?)(?:\s+(?:à|a|dans|de|en)\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => SearchDistrictsHandler::ACTION_KEY,
                'params' => array_filter([
                    'search' => trim($m[1]),
                    'city' => isset($m[2]) ? $this->titleCase(trim($m[2])) : null,
                ]),
            ];
        }

        return null;
    }

    private function titleCase(string $value): string
    {
        return mb_convert_case(trim($value), MB_CASE_TITLE, 'UTF-8');
    }
}
