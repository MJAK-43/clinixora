<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Compte administrateur local (développement).
     * Mot de passe : variable d'environnement ADMIN_SEED_PASSWORD ou défaut documenté dans docs/DEV_ACCOUNTS.md
     */
    public function run(): void
    {
        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        User::query()->updateOrCreate(
            ['email' => 'admin@clinixora.local'],
            [
                'name' => 'Administrateur Clinixora',
                'password' => Hash::make(config('clinixora.admin_seed_password', 'password')),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
