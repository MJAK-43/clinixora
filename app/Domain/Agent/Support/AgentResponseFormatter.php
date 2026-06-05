<?php

namespace App\Domain\Agent\Support;

use App\Domain\Agent\DTO\AgentActionResult;

class AgentResponseFormatter
{
    public const DEFAULT_PREVIEW_LIMIT = 6;

    /**
     * @param  list<string>  $itemLabels
     */
    public static function list(string $title, array $itemLabels, ?int $total = null, int $previewLimit = self::DEFAULT_PREVIEW_LIMIT): AgentActionResult
    {
        $total ??= count($itemLabels);

        if ($itemLabels === []) {
            return new AgentActionResult("{$title} : aucun résultat.");
        }

        return new AgentActionResult(
            "{$title} ({$total})",
            [
                'title' => $title,
                'list_items' => $itemLabels,
                'total' => $total,
                'preview_limit' => $previewLimit,
            ],
        );
    }
}
