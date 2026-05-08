<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 10);
            $table->string('flag', 10);
            $table->string('confederation', 20)->nullable();
            $table->foreignId('wc_group_id')->nullable()->constrained('wc_groups')->nullOnDelete();
            $table->integer('fifa_ranking')->nullable();
            $table->string('api_code', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
