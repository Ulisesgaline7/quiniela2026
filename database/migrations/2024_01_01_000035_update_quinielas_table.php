<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add group_picks table: user picks 2 teams per group to advance
        Schema::create('quiniela_group_picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiniela_id')->constrained()->onDelete('cascade');
            $table->foreignId('wc_group_id')->constrained('wc_groups');
            $table->foreignId('team_id')->constrained('teams');
            $table->integer('position')->default(1);
            $table->boolean('correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiniela_group_picks');
    }
};
