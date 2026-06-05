<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\CreateDistrictAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateDistrictHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.create_district';

    public function __construct(private readonly CreateDistrictAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer le quartier Bastos code BAS à Yaoundé';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Créer le quartier « {$params['name']} » ({$params['code']}) à {$params['city_name']}";
    }

    public function validateParams(array $params): array
    {
        $city = $this->resolveCity($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nom et code quartier requis.']);
        }

        return [
            'city_id' => $city->id,
            'city_name' => $city->name,
            'name' => $name,
            'code' => $code,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $city = $this->resolveCity(['city_id' => $params['city_id']]);

        try {
            $district = $this->action->execute($city, $params);
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer le quartier.']);
        }

        return new AgentActionResult("Le quartier « {$district->name} » ({$district->code}) a été créé à {$city->name}.");
    }
}
