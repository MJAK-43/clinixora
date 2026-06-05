<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\UpdateCityAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateCityHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.update_city';

    public function __construct(private readonly UpdateCityAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier la ville Yaoundé en Yaoundé code YAO dans Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Modifier la ville « {$params['city']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $city = $this->resolveCity($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'city_id' => $city->id,
            'city' => $city->name,
            'name' => $name,
            'code' => $code,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $city = $this->resolveCity(['city_id' => $params['city_id']]);
        $updated = $this->action->execute($city, $params);

        return new AgentActionResult("La ville a été mise à jour : « {$updated->name} » ({$updated->code}).");
    }
}
