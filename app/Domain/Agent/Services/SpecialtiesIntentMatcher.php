<?php

namespace App\Domain\Agent\Services;

use App\Domain\Agent\Handlers\Specialties\CreateSpecialtyHandler;
use App\Domain\Agent\Handlers\Specialties\DeleteSpecialtyHandler;
use App\Domain\Agent\Handlers\Specialties\ListSpecialtiesHandler;
use App\Domain\Agent\Handlers\Specialties\SearchSpecialtiesHandler;
use App\Domain\Agent\Handlers\Specialties\UpdateSpecialtyHandler;

class SpecialtiesIntentMatcher
{
    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $text): ?array
    {
        $matchers = [
            'matchDeleteSpecialty',
            'matchUpdateSpecialty',
            'matchCreateSpecialty',
            'matchSearchSpecialty',
            'matchListSpecialties',
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
    private function matchListSpecialties(string $text): ?array
    {
        if (preg_match('/(?:liste|lister|affiche|montre|voir)\s+(?:les\s+)?sp[eé]cialit[eé]s/u', $text)) {
            return ['action_key' => ListSpecialtiesHandler::ACTION_KEY, 'params' => []];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchCreateSpecialty(string $text): ?array
    {
        if (preg_match('/(?:crée|créer|ajoute|ajouter|nouvelle?)\s+(?:la\s+)?sp[eé]cialit[eé]\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => CreateSpecialtyHandler::ACTION_KEY,
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
    private function matchUpdateSpecialty(string $text): ?array
    {
        if (preg_match('/(?:modifie|modifier|renomme|renommer)\s+(?:la\s+)?sp[eé]cialit[eé]\s+(.+?)\s+(?:en|vers)\s+(.+?)\s+code\s+([a-z0-9]+)/ui', $text, $m)) {
            return [
                'action_key' => UpdateSpecialtyHandler::ACTION_KEY,
                'params' => [
                    'specialty' => $this->titleCase(trim($m[1])),
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
    private function matchDeleteSpecialty(string $text): ?array
    {
        if (preg_match('/(?:supprime|supprimer|efface|effacer)\s+(?:la\s+)?sp[eé]cialit[eé]\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => DeleteSpecialtyHandler::ACTION_KEY,
                'params' => ['specialty' => $this->titleCase(trim($m[1]))],
            ];
        }

        return null;
    }

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    private function matchSearchSpecialty(string $text): ?array
    {
        if (preg_match('/(?:recherche|rechercher|cherche|chercher|trouve|trouver)\s+(?:la\s+)?sp[eé]cialit[eé]\s+(.+)$/ui', $text, $m)) {
            return [
                'action_key' => SearchSpecialtiesHandler::ACTION_KEY,
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
