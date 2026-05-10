<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_predictions', function (Blueprint $table) {
            // New bonus fields per official rules
            $table->boolean('predict_both_score')->default(false)->after('predict_penalties');
            $table->boolean('predict_over3')->default(false)->after('predict_both_score');
            $table->boolean('predict_penalty_in_game')->default(false)->after('predict_over3');
            $table->boolean('predict_stoppage_goal')->default(false)->after('predict_penalty_in_game');
            // Update pts columns for new values
            $table->integer('pts_both_score')->default(0)->after('pts_penalties');
            $table->integer('pts_over3')->default(0)->after('pts_both_score');
            $table->integer('pts_penalty_in_game')->default(0)->after('pts_over3');
            $table->integer('pts_stoppage_goal')->default(0)->after('pts_penalty_in_game');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->boolean('had_penalty_in_game')->default(false)->after('had_red_card');
            $table->boolean('had_stoppage_goal')->default(false)->after('had_penalty_in_game');
        });

        // Special events table
        Schema::create('special_events', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // first_eliminated, first_qualified, first_00, first_hattrick, etc.
            $table->string('label');
            $table->integer('points');
            $table->foreignId('team_id')->nullable()->constrained('teams');
            $table->foreignId('match_id')->nullable()->constrained('matches');
            $table->string('player_name')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamps();
        });

        // User special event picks
        Schema::create('special_event_picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type');
            $table->foreignId('team_id')->nullable()->constrained('teams');
            $table->string('player_name')->nullable();
            $table->boolean('correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->timestamps();
            $table->unique(['user_id','event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_event_picks');
        Schema::dropIfExists('special_events');
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['had_penalty_in_game','had_stoppage_goal']);
        });
        Schema::table('match_predictions', function (Blueprint $table) {
            $table->dropColumn([
                'predict_both_score','predict_over3','predict_penalty_in_game','predict_stoppage_goal',
                'pts_both_score','pts_over3','pts_penalty_in_game','pts_stoppage_goal',
            ]);
        });
    }
};
