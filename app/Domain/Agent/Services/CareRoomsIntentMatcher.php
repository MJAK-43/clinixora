<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Handlers\CareRooms\CreateCareRoomHandler;
use App\Domain\Agent\Handlers\CareRooms\DeleteCareRoomHandler;
use App\Domain\Agent\Handlers\CareRooms\ListCareRoomsHandler;
use App\Domain\Agent\Handlers\CareRooms\SearchCareRoomsHandler;
use App\Domain\Agent\Handlers\CareRooms\UpdateCareRoomHandler;

class CareRoomsIntentMatcher
{
    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $text): ?array
    {
        $matchers = [
            'matchDeleteRoom',
            'matchUpdateRoom',
            'matchCreateRoom',
            'matchSearchRoom',
            'matchListRooms',
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
    private function matchListRooms(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?salles?\s+de\s+soin/u', $text)) {
            return ['action_key' => ListCareRoomsHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateRoom(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:la\s+)?salle\s+de\s+soin\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+service\s+(.+?))?(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => CreateCareRoomHandler::ACTION_KEY,
                'params' => array_filter([
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                    'service' => isset($m[3]) ? $this->titleCase(trim($m[3])) : 'Médecine générale',
                    'location' => isset($m[4]) ? trim($m[4]) : 'Niveau 1',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateRoom(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:la\s+)?salle\s+de\s+soin\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+service\s+(.+?))?(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => UpdateCareRoomHandler::ACTION_KEY,
                'params' => array_filter([
                    'care_room' => $this->titleCase(trim($m[1])),
                    'name' => $this->titleCase(trim($m[2])),
                    'code' => strtoupper(trim($m[3])),
                    'service' => isset($m[4]) ? $this->titleCase(trim($m[4])) : null,
                    'location' => isset($m[5]) ? trim($m[5]) : null,
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchDeleteRoom(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:la\s+)?salle\s+de\s+soin\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteCareRoomHandler::ACTION_KEY,
                'params' => ['care_room' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchRoom(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:la\s+)?salle\s+de\s+soin\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchCareRoomsHandler::ACTION_KEY,
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
