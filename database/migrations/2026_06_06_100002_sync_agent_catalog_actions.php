<?php

use Database\Seeders\AgentActionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    public function up(): void
    {
        Artisan::call('db:seed', [
            '--class' => AgentActionSeeder::class,
            '--force' => true,
        ]);
    }

    public function down(): void
    {
        // Les actions agent sont gérées par AgentActionSeeder (updateOrCreate).
    }
};
