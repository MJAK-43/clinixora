<?php

namespace App\Domain\Agent\Handlers\OperatingBlocks\Concerns;

use App\Domain\OperatingBlocks\Services\OperatingBlockQueryService;
use App\Domain\Services\Services\ServiceQueryService;
use App\Models\OperatingBlock;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

trait ResolvesOperatingBlockEntities
{
    protected function resolveOperatingBlock(array $params): OperatingBlock
    {
        if (isset($params['operating_block_id'])) {
            $block = OperatingBlock::query()->with('service')->find($params['operating_block_id']);
            if ($block) {
                return $block;
            }
        }

        $needle = trim((string) ($params['operating_block'] ?? $params['block'] ?? $params['name'] ?? $params['code'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['operating_block' => 'Indiquez le bloc (nom ou code).']);
        }

        $block = app(OperatingBlockQueryService::class)->findByNameOrCode($needle);

        if ($block === null) {
            throw ValidationException::withMessages(['operating_block' => "Bloc opératoire introuvable : « {$needle} »."]);
        }

        return $block;
    }

    protected function resolveServiceId(array $params): int
    {
        if (isset($params['service_id'])) {
            return (int) $params['service_id'];
        }

        $needle = trim((string) ($params['service'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['service' => 'Indiquez le service associé.']);
        }

        $service = app(ServiceQueryService::class)->findByNameOrCode($needle);

        if ($service === null) {
            throw ValidationException::withMessages(['service' => "Service introuvable : « {$needle} »."]);
        }

        return $service->id;
    }
}
