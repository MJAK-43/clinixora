<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\DeleteCityAction;
use App\Models\User;

class DeleteCityHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.delete_city';

    public function __construct(private readonly DeleteCityAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer la ville Testville dans Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer la ville « {$params['city']} »";
    }

    public function validateParams(array $params): array
    {
        $city = $this->resolveCity($params);

        return ['city_id' => $city->id, 'city' => $city->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $city = $this->resolveCity(['city_id' => $params['city_id']]);
        $name = $city->name;
        $this->action->execute($city);

        return new AgentActionResult("La ville « {$name} » et ses quartiers ont été supprimés.");
    }
}
