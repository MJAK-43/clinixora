<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\UpdateDistrictAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateDistrictHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.update_district';

    public function __construct(private readonly UpdateDistrictAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier le quartier Bastos en Bastos code BAS à Yaoundé';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Modifier le quartier « {$params['district']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $district = $this->resolveDistrict($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'district_id' => $district->id,
            'district' => $district->name,
            'name' => $name,
            'code' => $code,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $district = $this->resolveDistrict(['district_id' => $params['district_id']]);
        $updated = $this->action->execute($district, $params);

        return new AgentActionResult("Le quartier a été mis à jour : « {$updated->name} » ({$updated->code}).");
    }
}
