<?php

namespace App\Domain\Specialties\Actions;

use App\Models\Specialty;

class DeleteSpecialtyAction
{
    public function execute(Specialty $specialty): void
    {
        $specialty->delete();
    }
}
