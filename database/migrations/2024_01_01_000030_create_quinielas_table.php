<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quinielas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('closes_at')->nullable(); // 2026-06-10 23:59:00

            // Podio
            $table->foreignId('champion_id')->nullable()->constrained('teams');
            $table->foreignId('runner_up_id')->nullable()->constrained('teams');
            $table->foreignId('third_place_id')->nullable()->constrained('teams');

            // Premios individuales
            $table->string('golden_ball')->nullable();
            $table->string('golden_boot')->nullable();
            $table->string('golden_glove')->nullable();
            $table->string('best_young')->nullable();
            $table->foreignId('surprise_team_id')->nullable()->constrained('teams');

            // Estadísticas
            $table->foreignId('top_scorer_team_id')->nullable()->constrained('teams');
            $table->foreignId('best_defense_id')->nullable()->constrained('teams');
            $table->integer('total_goals_guess')->nullable();

            // Puntos calculados
            $table->integer('points_podio')->default(0);
            $table->integer('points_awards')->default(0);
            $table->integer('points_stats')->default(0);
            $table->integer('points_phases')->default(0);
            $table->integer('total_points')->default(0);

            $table->timestamps();
        });

        Schema::create('quiniela_phase_picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiniela_id')->constrained()->onDelete('cascade');
            $table->string('phase'); // groups_advance, round_of_16, quarters, semis, final
            $table->foreignId('team_id')->constrained('teams');
            $table->integer('points_earned')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiniela_phase_picks');
        Schema::dropIfExists('quinielas');
    }
};
