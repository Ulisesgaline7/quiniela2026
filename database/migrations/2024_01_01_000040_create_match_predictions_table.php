<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');

            // Marcador
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();

            // Resultado simple (local/empate/visitante)
            $table->string('result')->nullable(); // home, draw, away

            // Bonos
            $table->foreignId('first_scorer_team_id')->nullable()->constrained('teams');
            $table->boolean('predict_red_card')->default(false);
            $table->boolean('predict_extra_time')->default(false);
            $table->boolean('predict_penalties')->default(false);

            // Puntos desglosados
            $table->integer('pts_exact')->default(0);
            $table->integer('pts_result')->default(0);
            $table->integer('pts_diff')->default(0);
            $table->integer('pts_first_scorer')->default(0);
            $table->integer('pts_red_card')->default(0);
            $table->integer('pts_extra_time')->default(0);
            $table->integer('pts_penalties')->default(0);
            $table->integer('total_points')->default(0);

            $table->boolean('scored')->default(false); // has been graded

            $table->unique(['user_id', 'match_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_predictions');
    }
};
