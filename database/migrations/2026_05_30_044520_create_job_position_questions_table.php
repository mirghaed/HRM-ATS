<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_position_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained()->cascadeOnDelete();
            $table->string('question', 1000);
            $table->enum('type', ['text', 'textarea', 'number', 'select', 'radio', 'checkbox', 'file'])->default('text');
            $table->json('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('validation_rules')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_position_questions');
    }
};