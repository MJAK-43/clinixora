<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        /** @var list<array{name: string, code: string, description: string, icon: string, is_active: bool}> $rows */
        $rows = require database_path('data/medical_specialties.php');

        foreach ($rows as $row) {
            Specialty::query()->updateOrCreate(
                ['code' => strtoupper($row['code'])],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'icon' => $row['icon'],
                    'is_active' => $row['is_active'],
                ],
            );
        }
    }
}
