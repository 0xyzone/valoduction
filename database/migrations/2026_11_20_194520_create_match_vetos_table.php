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
        Schema::create('match_vetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_match_id')->constrained('game_matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('step_order');
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('action');
            $table->string('map_name');
            $table->string('side')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_vetos');
    }
};
