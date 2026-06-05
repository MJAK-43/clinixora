<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\DeleteDistrictAction;
use App\Models\User;

class DeleteDistrictHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.delete_district';

    public function __construct(private readonly DeleteDistrictAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer le quartier Testquartier à Yaoundé';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer le quartier « {$params['district']} »";
    }

    public function validateParams(array $params): array
    {
        $district = $this->resolveDistrict($params);

        return ['district_id' => $district->id, 'district' => $district->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $district = $this->resolveDistrict(['district_id' => $params['district_id']]);
        $name = $district->name;
        $this->action->execute($district);

        return new AgentActionResult("Le quartier « {$name} » a été supprimé.");
    }
}
