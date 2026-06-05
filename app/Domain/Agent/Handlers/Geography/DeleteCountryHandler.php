<?php

namespace App\Domain\Agent\Handlers\Geography;

use App\Domain\Agent\Concerns\AuthorizesGeographyAgent;
use App\Domain\Agent\Contracts\AgentActionHandler;
use App\Domain\Agent\DTO\AgentActionResult;
use App\Domain\Agent\Handlers\Geography\Concerns\ResolvesGeographyEntities;
use App\Domain\Geography\Actions\DeleteCountryAction;
use App\Domain\Geography\Exceptions\GeographyConflictException;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DeleteCountryHandler implements AgentActionHandler
{
    use AuthorizesGeographyAgent;
    use ResolvesGeographyEntities;

    public const ACTION_KEY = 'geography.delete_country';

    public function __construct(private readonly DeleteCountryAction $action) {}

    public function actionKey(): string
    {
        return self::ACTION_KEY;
    }

    public function syntaxTemplate(): string
    {
        return 'supprimer le pays Testland';
    }

    public function authorize(User $user): bool
    {
        return $this->canManageGeography($user);
    }

    public function describe(array $params): string
    {
        return "Supprimer le pays « {$params['country']} »";
    }

    public function validateParams(array $params): array
    {
        $country = $this->resolveCountry($params);

        return ['country_id' => $country->id, 'country' => $country->name];
    }

    public function execute(User $user, array $params): AgentActionResult
    {
        $country = $this->resolveCountry(['country_id' => $params['country_id']]);

        try {
            $this->action->execute($country);
        } catch (GeographyConflictException $e) {
            throw ValidationException::withMessages(['country' => $e->getMessage()]);
        }

        return new AgentActionResult("Le pays « {$country->name} » a été supprimé.");
    }
}
