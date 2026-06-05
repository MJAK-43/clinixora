<?php

namespace Database\Seeders;

use App\Models\ReceptionRoom;
use Illuminate\Database\Seeder;

class ReceptionRoomSeeder extends Seeder
{
    public function run(): void
    {
        /** @var list<array{name: string, code: string, location: string, icon: string, is_active: bool}> $rows */
        $rows = require database_path('data/reception_rooms.php');

        foreach ($rows as $row) {
            ReceptionRoom::query()->updateOrCreate(
                ['code' => strtoupper($row['code'])],
                [
                    'name' => $row['name'],
                    'location' => $row['location'],
                    'icon' => $row['icon'],
                    'is_active' => $row['is_active'],
                ],
            );
        }
    }
}
