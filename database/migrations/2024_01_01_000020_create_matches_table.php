<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('api_match_id')->nullable();
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->string('phase');
            $table->string('group_name')->nullable();
            $table->integer('matchday')->nullable();
            $table->dateTime('kickoff_at');
            $table->dateTime('closes_at')->nullable();
            $table->string('venue')->nullable();
            $table->string('city')->nullable();
            $table->string('status')->default('scheduled');
            $table->boolean('is_open')->default(true);
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->boolean('had_extra_time')->default(false);
            $table->boolean('had_penalties')->default(false);
            $table->boolean('had_red_card')->default(false);
            $table->foreignId('first_scorer_team_id')->nullable()->constrained('teams');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
