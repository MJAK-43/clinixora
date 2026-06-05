<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Handlers\OperatingBlocks\CreateOperatingBlockHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\DeleteOperatingBlockHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\ListOperatingBlocksHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\SearchOperatingBlocksHandler;
use App\Domain\Agent\Handlers\OperatingBlocks\UpdateOperatingBlockHandler;

class OperatingBlocksIntentMatcher
{
    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $text): ?array
    {
        $matchers = [
            'matchDeleteBlock',
            'matchUpdateBlock',
            'matchCreateBlock',
            'matchSearchBlock',
            'matchListBlocks',
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
    private function matchListBlocks(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?blocs?(?:\s+op[eé]ratoires?)?/u', $text)) {
            return ['action_key' => ListOperatingBlocksHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateBlock(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouveau)\s+(?:le\s+)?bloc(?:\s+op[eé]ratoire)?\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+service\s+(.+?))?(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => CreateOperatingBlockHandler::ACTION_KEY,
                'params' => array_filter([
                    'name' => $this->titleCase(trim($m[1])),
                    'code' => strtoupper(trim($m[2])),
                    'service' => isset($m[3]) ? $this->titleCase(trim($m[3])) : 'Chirurgie générale',
                    'location' => isset($m[4]) ? trim($m[4]) : 'Niveau 0',
                ]),
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchUpdateBlock(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:le\s+)?bloc(?:\s+op[eé]ratoire)?\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9-]+)(?:\s+service\s+(.+?))?(?:\s+localisation\s+(.+))?$/ui', $text, $m)) {
            return [
                'action_key' => UpdateOperatingBlockHandler::ACTION_KEY,
                'params' => array_filter([
                    'operating_block' => $this->titleCase(trim($m[1])),
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
    private function matchDeleteBlock(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:le\s+)?bloc(?:\s+op[eé]ratoire)?\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteOperatingBlockHandler::ACTION_KEY,
                'params' => ['operating_block' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchBlock(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:le\s+)?bloc(?:\s+op[eé]ratoire)?\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchOperatingBlocksHandler::ACTION_KEY,
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
