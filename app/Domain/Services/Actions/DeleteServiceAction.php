<?php

namespace App\Domain\Services\Actions;

use App\Models\Service;

class DeleteServiceAction
{
    public function execute(Service $service): void
    {
        $service->delete();
    }
}
