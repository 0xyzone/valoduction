<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_match_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_map_id')->constrained('match_maps')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('agent')->nullable();
            $table->unsignedSmallInteger('acs')->default(0);
            $table->unsignedSmallInteger('kills')->default(0);
            $table->unsignedSmallInteger('deaths')->default(0);
            $table->unsignedSmallInteger('assists')->default(0);
            $table->decimal('adr', 6, 2)->default(0);
            $table->decimal('hs_percentage', 5, 2)->default(0);
            $table->unsignedTinyInteger('first_kills')->default(0);
            $table->unsignedTinyInteger('first_deaths')->default(0);
            $table->unsignedInteger('credits_spent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_match_stats');
    }
};
