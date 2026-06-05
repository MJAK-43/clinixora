<?php

namespace App\Domain\Geography\Actions;

use App\Models\District;

class DeleteDistrictAction
{
    public function execute(District $district): void
    {
        $district->delete();
    }
}
