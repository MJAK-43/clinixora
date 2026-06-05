<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mode', 20)->default('mvp');
            $table->boolean('assistant_enabled')->default(true);
            $table->boolean('require_confirmation_writes')->default(true);
            $table->string('llm_provider')->nullable();
            $table->string('llm_model')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistant_settings');
    }
};
