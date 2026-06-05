<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action_key')->unique();
            $table->string('module', 50);
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('risk_level', 20)->default('medium');
            $table->boolean('requires_confirmation')->default(true);
            $table->boolean('is_enabled')->default(true);
            $table->string('handler_class');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_actions');
    }
};
