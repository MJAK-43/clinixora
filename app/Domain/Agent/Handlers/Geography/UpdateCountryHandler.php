<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\UpdateCountryAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateCountryHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.update_country';

    public function __construct(private readonly UpdateCountryAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'modifier le pays Cameroun en Cameroun code CM';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Modifier le pays « {$params['country']} » → « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $country = $this->resolveCountry($params);
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nouveau nom et code requis.']);
        }

        return [
            'country_id' => $country->id,
            'country' => $country->name,
            'name' => $name,
            'code' => $code,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $country = $this->resolveCountry(['country_id' => $params['country_id']]);
        $updated = $this->action->execute($country, $params);

        return new AgentActionResult("Le pays a été mis à jour : « {$updated->name} » ({$updated->code}).");
    }
}
