<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Handlers\ReceptionRooms\CreateReceptionRoomHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\DeleteReceptionRoomHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\ListReceptionRoomsHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\SearchReceptionRoomsHandler;
use App\Domain\Agent\Handlers\ReceptionRooms\UpdateReceptionRoomHandler;

class ReceptionRoomsIntentMatcher
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
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?salles?\s+d[\']?accueil/u', $text)) {
            return ['action_key' => ListReceptionRoomsHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateRoom(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:la\s+)?salle\s+d[\']?accueil\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => CreateReceptionRoomHandler::ACTION_KEY,
                'params' => array_filter([
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                    'location' => isset($m[3]) ? trim($m[3]) : 'Hall',
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
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:la\s+)?salle\s+d[\']?accueil\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => UpdateReceptionRoomHandler::ACTION_KEY,
                'params' => array_filter([
                    'reception_room' => $this->titleCase(trim($m[1])),
                    'name' => $this->titleCase(trim($m[2])),
                    'code' => strtoupper(trim($m[3])),
                    'location' => isset($m[4]) ? trim($m[4]) : null,
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
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:la\s+)?salle\s+d[\']?accueil\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteReceptionRoomHandler::ACTION_KEY,
                'params' => ['reception_room' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchRoom(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:la\s+)?salle\s+d[\']?accueil\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchReceptionRoomsHandler::ACTION_KEY,
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
