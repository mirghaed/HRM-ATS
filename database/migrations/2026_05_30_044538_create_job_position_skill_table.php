<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_position_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('weight')->default(1);
            $table->enum('min_level', ['beginner', 'intermediate', 'advanced', 'expert'])->nullable();
            $table->unsignedTinyInteger('min_years_experience')->nullable();
            $table->timestamps();

            $table->unique(['job_position_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_position_skill');
    }
};