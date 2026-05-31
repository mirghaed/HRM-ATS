<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_position_interviewer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['job_position_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_position_interviewer');
    }
};