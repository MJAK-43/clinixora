<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Geography\Actions\CreateCountryAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CreateCountryHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;

    public const ACTION_KEY = 'geography.create_country';

    public function __construct(private readonly CreateCountryAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'créer le pays Sénégal code SN';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Créer le pays « {$params['name']} » ({$params['code']})";
    }

    public function validateParams(array $params): array
    {
        $name = trim((string) ($params['name'] ?? ''));
        $code = strtoupper(trim((string) ($params['code'] ?? '')));

        if ($name === '' || $code === '') {
            throw ValidationException::withMessages(['name' => 'Nom et code pays requis.']);
        }

        return ['name' => $name, 'code' => $code];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        try {
            $country = $this->action->execute($params);
        } catch (\Throwable) {
            throw ValidationException::withMessages(['code' => 'Impossible de créer le pays (code peut-être déjà utilisé).']);
        }

        return new AgentActionResult("Le pays « {$country->name} » ({$country->code}) a été créé.");
    }
}
