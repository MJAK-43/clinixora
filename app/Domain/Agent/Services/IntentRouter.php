<?php

namespace App\Domain\Agent\Services;

class IntentRouter
{
    public function __construct(
        private readonly GeographyIntentMatcher $geography,
        private readonly SpecialtiesIntentMatcher $specialties,
        private readonly ClinicServicesIntentMatcher $services,
        private readonly OperatingBlocksIntentMatcher $operatingBlocks,
        private readonly CareRoomsIntentMatcher $careRooms,
        private readonly ReceptionRoomsIntentMatcher $receptionRooms,
    ) {}

    /**
     * @return array{action_key: string, params: array<string, mixed>}|null
     */
    public function match(string $message): ?array
    {
        $text = mb_strtolower(trim($message));

        if ($text === '') {
            return null;
        }

        if ($this->isHelpRequest($text)) {
            return ['action_key' => '_help', 'params' => []];
        }

        return $this->geography->match($text)
            ?? $this->specialties->match($text)
            ?? $this->services->match($text)
            ?? $this->operatingBlocks->match($text)
            ?? $this->careRooms->match($text)
            ?? $this->receptionRooms->match($text);
    }

    private function isHelpRequest(string $text): bool
    {
        return (bool) preg_match('/^(aide|help|que peux|comment utiliser|commandes)/u', $text);
    }
}
