<?php

namespace App\Domain\Agent\Handlers\Specialties\Concerns;

use App\Domain\Specialties\Services\SpecialtyQueryService;
use App\Models\Specialty;
use Illuminate\Validation\ValidationException;

trait ResolvesSpecialtyEntities
{
    protected function resolveSpecialty(array $params): Specialty
    {
        if (isset($params['specialty_id'])) {
            $specialty = Specialty::query()->find($params['specialty_id']);
            if ($specialty) {
                return $specialty;
            }
        }

        $needle = trim((string) ($params['specialty'] ?? $params['name'] ?? $params['code'] ?? ''));

        if ($needle === '') {
            throw ValidationException::withMessages(['specialty' => 'Indiquez la spécialité (nom ou code).']);
        }

        $specialty = app(SpecialtyQueryService::class)->findByNameOrCode($needle);

        if ($specialty === null) {
            throw ValidationException::withMessages(['specialty' => "Spécialité introuvable : « {$needle} »."]);
        }

        return $specialty;
    }
}
