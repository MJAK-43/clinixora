<?php

namespace Database\Seeders;

use App\Models\OperatingBlock;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OperatingBlockSeeder extends Seeder
{
    public function run(): void
    {
        /** @var list<array{name: string, code: string, service_code: string, location: string, icon: string, is_active: bool}> $rows */
        $rows = require database_path('data/operating_blocks.php');

        foreach ($rows as $row) {
            $service = Service::query()->where('code', $row['service_code'])->first();

            if ($service === null) {
                continue;
            }

            OperatingBlock::query()->updateOrCreate(
                ['code' => strtoupper($row['code'])],
                [
                    'service_id' => $service->id,
                    'name' => $row['name'],
                    'location' => $row['location'],
                    'icon' => $row['icon'],
                    'is_active' => $row['is_active'],
                ],
            );
        }
    }
}
