<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Handlers\Services\CreateServiceHandler;
use App\Domain\Agent\Handlers\Services\DeleteServiceHandler;
use App\Domain\Agent\Handlers\Services\ListServicesHandler;
use App\Domain\Agent\Handlers\Services\SearchServicesHandler;
use App\Domain\Agent\Handlers\Services\UpdateServiceHandler;

class ClinicServicesIntentMatcher
{
    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $text): ?array
    {
        $matchers = [
            'matchDeleteService',
            'matchUpdateService',
            'matchCreateService',
            'matchSearchService',
            'matchListServices',
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
    private function matchListServices(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?services/u', $text)) {
            return ['action_key' => ListServicesHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateService(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:le\s+)?service\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => CreateServiceHandler::ACTION_KEY,
                'params' => [
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                ],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateService(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:le\s+)?service\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => UpdateServiceHandler::ACTION_KEY,
                'params' => [
                    'service' => $this->titleCase(trim($m[1])),
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
    private function matchDeleteService(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:le\s+)?service\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteServiceHandler::ACTION_KEY,
                'params' => ['service' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchService(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:le\s+)?service\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchServicesHandler::ACTION_KEY,
                'params' => ['search' => trim($m[1])],
            ];
        }

        return null;
    }

    private function titleCase(string $value): string
    {
        return mb_convert_case(trim($value), MB_CASE_TITLE, 'UTF-8');
    }
}
