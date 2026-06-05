<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\CreateCityAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateCityHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.create_city';

    public function __construct(
        private readonly CreateCityAction $createCity,
    ) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer la ville Ngaoundéré code NGA dans Cameroun';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        $country = $params['country_name'] ?? 'le pays';

        return "Créer la ville « {$params['name']} » ({$params['code']}) dans {$country}";
    }

    public function validateParams(array $params): array
    {
        $country = $this->resolveCountry($params);
        $name = trim((string) ($params['name'] ?? $params['city'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '') {
            throw ValidationException::withMessages(['name' => 'Le nom de la ville est requis.']);
        }

        if ($code === '' || strlen($code) > 10) {
            throw ValidationException::withMessages(['code' => 'Le code ville est requis (10 caractères max).']);
        }

        return [
            'country_id' => $country->id,
            'country_name' => $country->name,
            'name' => $name,
            'code' => $code,
        ];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $country = $this->resolveCountry(['country_id' => $params['country_id']]);

        try {
            $city = $this->createCity->execute($country, [
                'name' => $params['name'],
                'code' => $params['code'],
            ]);
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'code' => 'Impossible de créer la ville. Le code existe peut-être déjà pour ce pays.',
            ]);
        }

        return new AgentActionResult(
            "La ville « {$city->name} » ({$city->code}) a été créée dans {$country->name}.",
            ['city_id' => $city->id],
        );
    }
}
