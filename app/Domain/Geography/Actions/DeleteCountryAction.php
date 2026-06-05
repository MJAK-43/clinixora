<?php

namespace App\Domain\Geography\Actions;

use App\Domain\Geography\Exceptions\GeographyConflictException;
use App\Models\Country;

class DeleteCountryAction
{
    public function execute(Country $country): void
    {
        if ($country->cities()->exists()) {
            throw new GeographyConflictException('Ce pays contient encore des villes. Supprimez-les d\'abord.');
        }

        $country->delete();
    }
}
