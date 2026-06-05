<?php

namespace App\Domain\Geography\Actions;

use App\Models\City;
use Illuminate\Support\Facades\DB;

class DeleteCityAction
{
    public function execute(City $city): void
    {
        DB::transaction(function () use ($city): void {
            $city->districts()->delete();
            $city->delete();
        });
    }
}
