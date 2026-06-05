<?php

namespace App\Domain\Agent\Handlers\Services\Concerns;

use App\Domain\Services\Services\ServiceQueryService;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

trait ResolvesServiceEntities
{
    protected function resolveService(array $params): Service
    {
        if (isset($params['service_id'])) {
            $service = Service::query()->find($params['service_id']);
            if ($service) {
                return $service;
            }
        }

        $needle = trim((string) ($params['service'] ?? $params['name'] ?? $params['code'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['service' => 'Indiquez le service (nom ou code).']);
        }

        $service = app(ServiceQueryService::class)->findByNameOrCode($needle);

        if ($service === null) {
            throw ValidationException::withMessages(['service' => "Service introuvable : « {$needle} »."]);
        }

        return $service;
    }
}
